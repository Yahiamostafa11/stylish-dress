@php
    $navClass = $navClass ?? 'main-nav';
    $navId = $navId ?? null;
    $currentLocale = $currentLocale ?? 'ar';
    $isEnglish = ($isEnglish ?? ($currentLocale === 'en')) === true;
    $translate = (isset($t) && is_callable($t)) ? $t : fn (string $key) => $key;
    $nt = function (string $key, string $arFallback, string $enFallback) use ($translate, $isEnglish): string {
        $value = (string) $translate($key);
        if ($value === '' || $value === $key) {
            return $isEnglish ? $enFallback : $arFallback;
        }

        return $value;
    };

    $normalizePath = function (string $path): string {
        $trimmed = trim($path);
        if ($trimmed === '' || $trimmed === '/') {
            return '/';
        }

        return '/' . trim($trimmed, '/');
    };

    $currentPath = $normalizePath(request()->getPathInfo());
    $homePath = $normalizePath($localePrefix ?? '/');
    $shopPath = $normalizePath(($localePrefix ?? '/') . '/shop');
    $blogPath = $normalizePath(($localePrefix ?? '/') . '/blog');
    $aboutPath = $normalizePath(($localePrefix ?? '/') . '/about-us');
    $contactPath = $normalizePath(($localePrefix ?? '/') . '/contact-us');

    $isActive = fn (string $targetPath) => $currentPath === $targetPath;
@endphp
<nav @if($navId) id="{{ $navId }}" @endif class="{{ $navClass }}" aria-label="Main Navigation">
    <a class="{{ $isActive($homePath) ? 'active' : '' }}" href="{{ $localePrefix }}">{{ $nt('nav_home', 'الرئيسية', 'Home') }}</a>
    <a class="{{ $isActive($shopPath) ? 'active' : '' }}" href="{{ $localePrefix }}/shop">{{ $nt('nav_shop', 'المتجر', 'Shop') }}</a>
    <a class="{{ $isActive($blogPath) ? 'active' : '' }}" href="{{ $localePrefix }}/blog">{{ $nt('nav_blog', 'المدونة', 'Blog') }}</a>
    <a class="{{ $isActive($aboutPath) ? 'active' : '' }}" href="{{ $localePrefix }}/about-us">{{ $nt('about_us', 'من نحن', 'About') }}</a>
    <a class="{{ $isActive($contactPath) ? 'active' : '' }}" href="{{ $localePrefix }}/contact-us">{{ $nt('nav_contact', 'تواصل معنا', 'Contact') }}</a>
</nav>
