<?php

namespace App\Core;

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $config = App::config('app');
        $csrfToken = Security::generateCsrf();
        $user = Auth::user();
        $flash = Session::getFlash();

        $viewFile = App::basePath() . "/app/Views/{$template}.php";
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "View not found: {$template}";
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if (str_starts_with($template, 'layouts/') || str_starts_with($template, 'errors/')) {
            echo $content;
            return;
        }

        $publicTemplates = ['auth/', 'applications/form', 'applications/success', 'home/'];
        $isPublic = false;
        foreach ($publicTemplates as $prefix) {
            if (str_starts_with($template, $prefix)) {
                $isPublic = true;
                break;
            }
        }

        require App::basePath() . '/app/Views/layouts/' . ($isPublic ? 'public' : 'app') . '.php';
    }

    public static function partial(string $partial, array $data = []): void
    {
        if (!isset($data['csrfToken'])) {
            $data['csrfToken'] = Security::generateCsrf();
        }
        extract($data);
        require App::basePath() . "/app/Views/partials/{$partial}.php";
    }

    public static function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    public static function label(string $key, bool $required = false, ?string $for = null): void
    {
        self::partial('bilingual-label', compact('key', 'required', 'for'));
    }

    public static function heading(string $key, string $tag = 'h5', string $icon = '', string $class = ''): void
    {
        self::partial('bilingual-heading', compact('key', 'tag', 'icon', 'class'));
    }

    public static function text(string $key, string $tag = 'span', bool $block = false, string $class = ''): void
    {
        self::partial('bilingual-text', compact('key', 'tag', 'block', 'class'));
    }

    public static function labelRaw(string $ta, string $en, bool $required = false, ?string $for = null): void
    {
        self::partial('bilingual-label', compact('ta', 'en', 'required', 'for'));
    }

    public static function placeholder(string $key): string
    {
        return \App\Helpers\Lang::placeholder($key);
    }

    public static function assetBase(): string
    {
        $publicBase = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $appBase = dirname($publicBase);
        $base = ($appBase !== '/' && $appBase !== '.') ? $appBase : $publicBase;

        return $base === '/' ? '' : $base;
    }
}
