<?php

namespace Eshop\Livewire\Dashboard\Product;

use Eshop\Actions\Audit\AuditModel;
use Eshop\Actions\InsertWatermark;
use Eshop\Models\Product\Channel;
use Eshop\Models\Product\Product;
use Eshop\Models\Product\VariantType;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VariantsTable extends Component
{
    use SendsNotifications;

    public $product;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function getVariantsProperty(): Collection
    {
        $variants = Product::query()
            ->where('parent_id', $this->product->id)
            ->when($this->search, function ($q, $v) {
                $q->where(function ($b) use ($v) {
                    $b->where('sku', 'LIKE', "%$v%");
                    $b->orWhereHas('variantOptions.translation', fn($b) => $b->where('translation', 'LIKE', "%$v%"));
                });
            })
            ->with(['options.translation', 'category', 'image', 'channels'])
            ->get()
            ->each(fn($v) => $v->options = $v->options->sortBy('position'));

        (new \Illuminate\Database\Eloquent\Collection($variants->pluck('options')->flatten()->pluck('pivot')))->load('translation');

        return $variants
            ->sortBy(fn($v) => $v->options->pluck('pivot.name')->join(' / '), SORT_NATURAL | SORT_FLAG_CASE);
    }

    public function getVariantTypesProperty(): Collection
    {
        return VariantType::where('product_id', $this->product->id)->get();
    }

    public function toggleVisible(array $ids, bool $visible, AuditModel $audit): void
    {
        DB::transaction(function () use ($ids, $visible, $audit) {
            Product::whereKey($ids)->update([
                'visible' => $visible
            ]);

            $variants = $this->variants;
            $variants->load('manufacturer', 'translations', 'seos', 'unit', 'parent.translations');
            foreach ($this->variants as $variant) {
                $audit->handle($variant);
            }

            $this->showSuccessToast("Οι αλλαγές αποθηκεύτηκαν!");
        });
    }

    public function toggleRecent(array $ids, bool $recent, AuditModel $audit): void
    {
        DB::transaction(function () use ($ids, $recent, $audit) {
            Product::whereKey($ids)->update([
                'recent' => $recent
            ]);

            $variants = $this->variants;
            $variants->load('manufacturer', 'translations', 'seos', 'unit', 'parent.translations');
            foreach ($this->variants as $variant) {
                $audit->handle($variant);
            }

            $this->showSuccessToast("Οι αλλαγές αποθηκεύτηκαν!");
        });
    }

    public function addWatermark(array $ids, InsertWatermark $watermark): void
    {
        if (empty($ids)) {
            return;
        }

        $products = Product::whereKey($ids)->with('image')->get();
        foreach ($products as $product) {
            if ($product->image) {
                $image = $watermark->handle($product->image->path());
                $product->image->addConversion('wm', $image);
            }
        }

        Product::whereKey($ids)->update(['has_watermark' => true]);
    }

    public function removeWatermark(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $products = Product::whereKey($ids)->with('image')->get();
        foreach ($products as $product) {
            $product->image?->deleteConversion('wm');
        }

        Product::whereKey($ids)->update(['has_watermark' => false]);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.variant.wire.variants-table', [
            'variants'     => $this->variants,
            'variantTypes' => $this->variantTypes,
            'channels'     => Channel::orderBy('name')->get()
        ]);
    }

    public function toggleChannel(Channel $channel, array $ids, bool $visible): void
    {
        DB::transaction(function () use ($channel, $ids, $visible) {
            if ($visible) {
                $channel->products()->syncWithoutDetaching($ids);
            } else {
                $channel->products()->detach($ids);
            }

            $this->showSuccessToast("Οι αλλαγές αποθηκεύτηκαν!");
        });
    }

    public function saveChannelPrices(int $channel_id, array $product_ids, ?string $price, ?string $discount): void
    {
        DB::table('channel_product')
            ->where('channel_id', $channel_id)
            ->whereIntegerInRaw('product_id', $product_ids)
            ->update([
                'price'    => $price === '' ? null : $price,
                'discount' => $discount === '' ? null : $discount/100
            ]);

        $this->showSuccessToast("Οι τιμές ενημερώθηκαν με επιτυχία!");
    }

    protected function getModels(): Collection
    {
        return $this->variants;
    }
}
