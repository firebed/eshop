<?php

namespace Eshop\Mail;

use Eshop\Models\Blog\Blog;
use Illuminate\Mail\Mailable;

class BlogMail extends Mailable
{
    public Blog $blog;

    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    public function build(): self
    {
        return $this->subject($this->blog->title)
            ->markdown('eshop::customer.emails.blog.mail');
    }
}
