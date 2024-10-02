<?php

namespace App\Twig\Components;

use App\Twig\Components\Dto\ElementAttributes;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent]
class Modal
{
    public string $title;
    public string $message;
    public string $confirm;
    public string $cancel;
    public string $onConfirm;
    public string $onCancel;
    public string $target;
    public string $titleTag;
    public string $btnText;

    public ElementAttributes $btnAttrs;

    use DefaultActionTrait;

    #[PreMount]
    public function preMount(array $data): void
    {
        $this->title = $data['title'] ?? 'Are you sure?';
        $this->message = $data['message'] ?? 'Are you sure you want to continue?';
        $this->confirm = $data['confirm'] ?? 'Yes';
        $this->cancel = $data['cancel'] ?? 'No';
        $this->onConfirm = $data['onConfirm'] ?? '() => {}';
        $this->onCancel = $data['onCancel'] ?? '() => {}';
        $this->target = $data['target'] ?? 'modal-' . random_int(1000, 9999);
        $this->titleTag = $data['titleTag'] ?? 'h5';
        $this->btnText = $data['btnText'] ?? 'Open Modal';
        $this->btnAttrs = new ElementAttributes($data['btnAttrs'] ?? []);
    }
}
