<?php

namespace Eshop\Controllers\Dashboard;

use Eshop\Models\Product\Channel;
use Illuminate\Contracts\Support\Renderable;

class ChannelController extends Controller
{
    public function index(): Renderable
    {
        $channels = Channel::withCount('products')->get();

        return $this->view('channel.index', compact('channels'));
    }

    public function edit(Channel $channel): Renderable
    {
        return $this->view('channel.edit', compact('channel'));
    }
}