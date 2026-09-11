<style>
    .promo {
        background: linear-gradient(90deg, var(--secondary), #24384f);
        color: #fff;
        text-align: center;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
    }

    .topbar {
        background: var(--secondary);
        color: #fff;
        font-size: 13px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .topbar-inner {
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .topbar-left,
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .topbar a {
        color: #fff;
        opacity: .92;
    }

    .topbar a:hover {
        opacity: 1;
    }

    .topbar-phone {
        direction: ltr;
        unicode-bidi: isolate;
        display: inline-block;
        letter-spacing: .2px;
        font-variant-numeric: tabular-nums;
    }

    .topbar-desktop-contact {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .topbar-mobile-social {
        display: none;
        position: fixed;
        inset-inline-end: 14px;
        bottom: 18px;
        z-index: 120;
    }

    .topbar-mobile-lang {
        display: none;
        position: fixed;
        inset-inline-end: 14px;
        bottom: 86px;
        z-index: 121;
    }

    .topbar-lang-toggle {
        min-width: 56px;
        height: 44px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.72);
        background: rgba(15, 26, 42, 0.98);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .6px;
        box-shadow: 0 10px 24px rgba(10, 17, 30, 0.35);
        cursor: pointer;
        transition: transform .28s cubic-bezier(.22, .61, .36, 1), box-shadow .28s ease;
    }

    .topbar-lang-toggle[aria-expanded="true"] {
        transform: translateX(-2px);
        box-shadow: 0 14px 28px rgba(10, 17, 30, 0.46);
    }

    .topbar-lang-panel {
        position: absolute;
        bottom: 0;
        inset-inline-end: calc(100% + 8px);
        display: grid;
        gap: 6px;
        padding: 8px;
        min-width: 56px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(15, 26, 42, 0.94);
        box-shadow: 0 12px 28px rgba(10, 17, 30, 0.34);
        opacity: 0;
        transform: translateX(10px);
        pointer-events: none;
        transition: opacity .2s ease, transform .25s cubic-bezier(.22, .61, .36, 1);
    }

    .topbar-lang-panel.is-open {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    .topbar-lang-panel a {
        height: 30px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .topbar-lang-panel a.active {
        background: #fff;
        color: var(--secondary);
    }

    .topbar-social-toggle {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.72);
        background: rgba(15, 26, 42, 0.98);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 14px 32px rgba(10, 17, 30, 0.45), 0 0 0 3px rgba(255, 255, 255, 0.08);
        cursor: pointer;
        transition: transform .4s cubic-bezier(.22, .61, .36, 1), box-shadow .34s ease, background .34s ease;
    }

    .topbar-social-toggle svg {
        width: 24px;
        height: 24px;
        fill: currentColor;
        transition: transform .4s cubic-bezier(.22, .61, .36, 1);
    }

    .topbar-social-toggle[aria-expanded="true"] {
        transform: rotate(180deg) scale(1.06);
        box-shadow: 0 18px 36px rgba(10, 17, 30, 0.52), 0 0 0 4px rgba(213, 21, 34, 0.18);
        background: rgba(15, 26, 42, 1);
    }

    .topbar-social-toggle[aria-expanded="true"] svg {
        transform: scale(1.14);
    }

    .topbar-mobile-icons {
        position: absolute;
        bottom: 0;
        inset-inline-end: calc(100% + 8px);
        z-index: 80;
        min-width: 48px;
        padding: 8px;
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(15, 26, 42, 0.94);
        box-shadow: 0 14px 30px rgba(10, 17, 30, 0.32);
        transition: transform .28s cubic-bezier(.22, .61, .36, 1), box-shadow .28s ease, background .28s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 9px;
        opacity: 0;
        transform: translateX(16px) scale(.9);
        pointer-events: none;
        transition: opacity .24s ease, transform .38s cubic-bezier(.22, .61, .36, 1);
    }

    .topbar-mobile-icons.is-open {
        opacity: 1;
        transform: translateX(0) scale(1);
        pointer-events: auto;
    }

    .topbar-mobile-icon {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        background: rgba(255, 255, 255, 0.14);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        line-height: 1;
        box-shadow: 0 3px 10px rgba(10, 17, 30, 0.2);
        opacity: 0;
        transform: translateX(14px) scale(.78);
        transition: transform .34s cubic-bezier(.22, .61, .36, 1), opacity .26s ease, background .24s ease;
    }

    .topbar-mobile-icons.is-open .topbar-mobile-icon {
        opacity: 1;
        transform: translateX(0) scale(1);
    }

    .topbar-mobile-icons .topbar-mobile-icon:nth-child(1) { transition-delay: .03s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(2) { transition-delay: .07s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(3) { transition-delay: .11s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(4) { transition-delay: .15s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(5) { transition-delay: .19s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(6) { transition-delay: .23s; }

    .topbar-mobile-icon:hover {
        transform: translateY(-2px) scale(1.06);
        background: rgba(255, 255, 255, 0.22);
    }

    .topbar-mobile-icon svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    .topbar-mobile-icon.icon-call { color: #ffffff; }
    .topbar-mobile-icon.icon-whatsapp { color: #25D366; }
    .topbar-mobile-icon.icon-facebook { color: #1877F2; }
    .topbar-mobile-icon.icon-instagram { color: #E1306C; }
    .topbar-mobile-icon.icon-tiktok { color: #111111; }
    .topbar-mobile-icon.icon-google { color: #4285F4; }

    .topbar-mobile-icon.icon-google svg {
        width: 14px;
        height: 14px;
    }

    .topbar-note {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        padding: 4px 10px;
        font-weight: 700;
        font-size: 12px;
    }

    .site-footer .footer-brand-logo {
        height: 62px !important;
        width: auto !important;
        max-width: min(360px, 100%) !important;
        filter: none !important;
        opacity: 1 !important;
    }

    .site-footer {
        margin-top: 8px;
        background: #0f1a2a;
        color: #fff;
        border-top: 4px solid var(--primary);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr 1fr;
        gap: 18px;
        padding: 34px 0 22px;
    }

    .footer-brand,
    .footer-col {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 16px;
    }

    .footer-brand h4,
    .footer-col h5 {
        margin: 0 0 10px;
        font-size: 18px;
        color: #fff;
    }

    .footer-brand p {
        margin: 0 0 10px;
        color: #b8c2d1;
        font-size: 14px;
    }

    .footer-status,
    .footer-open-hours {
        margin: 0 0 10px;
        color: #b8c2d1;
        font-size: 14px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 3px 9px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid transparent;
        line-height: 1.2;
    }

    .status-pill.is-open {
        color: #0a8f5b;
        border-color: rgba(10, 143, 91, 0.45);
        background: rgba(10, 143, 91, 0.14);
    }

    .status-pill.is-closed {
        color: var(--primary);
        border-color: rgba(213, 21, 34, 0.45);
        background: rgba(213, 21, 34, 0.14);
    }

    .footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 7px;
    }

    .footer-links a {
        color: #b8c2d1;
        font-size: 14px;
        transition: .2s ease;
    }

    .footer-links a:hover {
        color: #fff;
    }

    .footer-brand .footer-contact-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .footer-brand .footer-contact-row a {
        color: #fff;
        background: rgba(213, 21, 34, 0.16);
        border: 1px solid rgba(213, 21, 34, 0.35);
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.14);
        padding: 12px 0 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        color: #b8c2d1;
        font-size: 13px;
    }

    .footer-bottom a {
        color: #fff;
    }

    .footer-mini-nav {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
        padding-bottom: 18px;
    }

    .footer-mini-nav a {
        color: #b8c2d1;
        font-size: 13px;
    }

    .footer-mini-nav a:hover {
        color: #fff;
    }

    .lang-switch {
        position: relative;
        display: inline-grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        direction: ltr;
        width: 110px;
        height: 34px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 999px;
        padding: 3px;
        overflow: hidden;
    }

    .lang-switch .lang-indicator {
        position: absolute;
        top: 3px;
        width: calc(50% - 3px);
        height: calc(100% - 6px);
        background: #fff;
        border-radius: 999px;
        transition: .25s ease;
        z-index: 1;
    }

    .lang-switch.is-ar .lang-indicator {
        left: 3px;
    }

    .lang-switch.is-en .lang-indicator {
        right: 3px;
    }

    .lang-switch a {
        position: relative;
        z-index: 2;
        text-align: center;
        font-size: 12px;
        font-weight: 800;
        opacity: .95;
        color: #fff;
        padding: 5px 0;
    }

    .lang-switch a.active {
        color: var(--secondary);
        opacity: 1;
    }

    .main-header {
        background: #fff;
        border-bottom: 1px solid var(--line);
        position: sticky;
        top: 0;
        z-index: 40;
        box-shadow: 0 8px 24px rgba(23, 39, 59, 0.06);
    }

    .main-header-inner {
        min-height: 96px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 16px;
    }

    .brand {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .brand-logo {
        height: 60px;
        width: auto;
        max-width: min(320px, 52vw);
        object-fit: contain;
    }

    .brand-tag {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
    }

    .main-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        background: #f9fbff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 6px;
    }

    .main-nav a {
        color: var(--secondary);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 12px;
        border-radius: 8px;
        transition: .2s ease;
        text-decoration: none;
    }

    .main-nav a:hover {
        color: var(--primary);
        background: #fff4f5;
    }

    .main-nav a.active {
        background: #fff4f5;
        color: var(--primary);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .header-categories-strip {
        background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
        border-bottom: 1px solid rgba(23, 39, 59, 0.08);
    }

    .categories-strip-inner {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 0;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .category-strip-group {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding-inline-end: 6px;
        border-inline-end: 1px dashed rgba(23, 39, 59, 0.14);
    }

    .category-strip-group:last-child {
        border-inline-end: 0;
        padding-inline-end: 0;
    }

    .categories-strip-inner::-webkit-scrollbar {
        display: none;
    }

    .category-strip-chip {
        flex: 0 0 auto;
        min-height: 36px;
        border: 1px solid rgba(23, 39, 59, 0.12);
        border-radius: 999px;
        background: #ffffff;
        color: var(--secondary);
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(23, 39, 59, 0.05);
        transition: transform .2s ease, border-color .2s ease, color .2s ease, background-color .2s ease, box-shadow .2s ease;
        animation: categoryChipIn .35s ease both;
    }

    .category-strip-parent {
        background: #fff4f5;
        border-color: rgba(213, 21, 34, 0.26);
        color: var(--primary);
    }

    .category-strip-sub {
        min-height: 32px;
        font-size: 12px;
        padding: 0 11px;
        font-weight: 700;
    }

    .category-strip-chip:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #fff4f5;
        box-shadow: 0 6px 14px rgba(23, 39, 59, 0.1);
        transform: translateY(-2px);
    }

    .category-strip-chip.is-active {
        border-color: var(--primary);
        color: #fff;
        background: var(--primary);
        box-shadow: 0 8px 18px rgba(213, 21, 34, 0.28);
        transform: translateY(-1px);
    }

    .category-strip-chip.is-active:hover {
        color: #fff;
        background: var(--primary);
    }

    @keyframes categoryChipIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .action-account,
    .action-wishlist,
    .action-cart,
    .action-sell,
    .action-categories {
        white-space: nowrap;
    }

    .header-categories {
        position: relative;
        display: inline-flex;
    }

    .header-categories > summary {
        list-style: none;
    }

    .header-categories > summary::-webkit-details-marker {
        display: none;
    }

    .category-trigger {
        min-height: 40px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        color: var(--secondary);
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        font-family: inherit;
        transition: .2s ease;
    }

    .category-trigger:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .category-menu-panel {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: min(240px, 72vw);
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(23, 39, 59, .14);
        padding: 8px;
        display: grid;
        gap: 4px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity .22s ease, transform .22s ease, visibility .22s ease;

    .topbar-mobile-icons.is-open .topbar-mobile-icon {
        opacity: 1;
        transform: translateX(0) scale(1);
    }

    .topbar-mobile-icons .topbar-mobile-icon:nth-child(1) { transition-delay: .02s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(2) { transition-delay: .05s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(3) { transition-delay: .08s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(4) { transition-delay: .11s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(5) { transition-delay: .14s; }
    .topbar-mobile-icons .topbar-mobile-icon:nth-child(6) { transition-delay: .17s; }
        z-index: 90;
    }

    [dir="rtl"] .category-menu-panel { right: auto; left: 0; }
    [dir="ltr"] .category-menu-panel { left: auto; right: 0; }

    .header-categories:hover .category-menu-panel,
    .header-categories:focus-within .category-menu-panel,
    .header-categories[open] .category-menu-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .category-menu-panel a {
        min-height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        padding: 0 10px;
        color: var(--secondary);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .category-menu-panel a:hover {
        background: #fff4f5;
        color: var(--primary);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background: var(--primary-2);
        border-color: var(--primary-2);
    }

    .search-form {
        display: flex;
        align-items: center;
        height: 40px;
        border: 1px solid var(--line);
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }

    .search-input {
        border: 0;
        outline: 0;
        width: 190px;
        padding: 0 12px;
        color: var(--secondary);
        font-size: 13px;
        font-family: inherit;
    }

    .search-btn {
        height: 100%;
        border: 0;
        background: var(--secondary);
        color: #fff;
        padding: 0 12px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
    }

    .search-btn:hover {
        background: #24384f;
    }

    .icon-btn {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--secondary);
        border-radius: 10px;
        min-width: 38px;
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        font-size: 13px;
        font-weight: 700;
    }

    .icon-btn .icon {
        font-size: 16px;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .icon-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .wishlist-trigger-wrap,
    .cart-trigger-wrap,
    .account-trigger-wrap {
        position: relative;
    }

    .account-menu {
        position: absolute;
        top: calc(100% + 8px);
        inset-inline-end: 0;
        width: min(260px, 78vw);
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 12px 26px rgba(23, 39, 59, 0.18);
        padding: 10px;
        display: none;
        z-index: 70;
    }

    .account-menu.is-open {
        display: grid;
        gap: 6px;
    }

    .account-menu-head {
        border-bottom: 1px solid var(--line);
        padding-bottom: 8px;
        margin-bottom: 2px;
        display: grid;
        gap: 2px;
    }

    .account-menu-head strong {
        color: var(--secondary);
        font-size: 13px;
        line-height: 1.35;
    }

    .account-menu-head span {
        color: var(--muted);
        font-size: 12px;
    }

    .account-menu a {
        min-height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    #accountMenuManage {
        background: var(--secondary);
        color: #fff;
    }

    #accountMenuLogout {
        background: #fff;
        border: 1px solid var(--line);
        color: var(--secondary);
    }

    .wishlist-count,
    .cart-count {
        position: absolute;
        top: -8px;
        right: -8px;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        line-height: 18px;
        text-align: center;
        font-weight: 800;
        padding: 0 4px;
        display: none;
    }

    .wishlist-plus-one,
    .cart-plus-one {
        position: absolute;
        top: -24px;
        right: -4px;
        font-size: 12px;
        font-weight: 900;
        color: var(--primary);
        opacity: 0;
        transform: translateY(0);
        pointer-events: none;
    }

    .wishlist-dropdown,
    .account-mini-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(23, 39, 59, .14);
        padding: 10px;
        display: none;
        z-index: 90;
    }

    .wishlist-dropdown { width: min(360px, 82vw); }
    .account-mini-menu { width: min(320px, 86vw); }

    [dir="rtl"] .wishlist-dropdown,
    [dir="rtl"] .account-mini-menu { right: auto; left: 0; }

    [dir="ltr"] .wishlist-dropdown,
    [dir="ltr"] .account-mini-menu { left: auto; right: 0; }

    .wishlist-dropdown.is-open,
    .account-mini-menu.is-open { display: block; }

    .account-mini-head {
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fbfcff;
        padding: 10px;
        display: grid;
        gap: 4px;
        margin-bottom: 10px;
    }

    .account-mini-name {
        font-size: 14px;
        font-weight: 900;
        color: var(--secondary);
    }

    .account-mini-meta {
        font-size: 12px;
        color: var(--muted);
    }

    .account-mini-actions {
        display: grid;
        gap: 8px;
    }

    .account-mini-actions a {
        min-height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .account-manage-link {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--secondary);
    }

    .account-logout-link {
        border: 1px solid rgba(var(--wf-main-rgb), .25);
        background: #fff7f8;
        color: var(--primary);
    }

    .wishlist-dropdown-list {
        display: grid;
        gap: 8px;
        max-height: 360px;
        overflow: auto;
    }

    .wishlist-dropdown-item {
        display: grid;
        grid-template-columns: 56px 1fr;
        gap: 10px;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 8px;
        background: #fff;
    }

    .wishlist-dropdown-item img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        background: #f2f2f5;
    }

    .wishlist-dropdown-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--secondary);
        margin: 0 0 6px;
        line-height: 1.35;
    }

    .wishlist-dropdown-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .wishlist-dropdown-empty {
        margin: 0;
        font-size: 13px;
        color: var(--muted);
        text-align: center;
        padding: 12px 8px;
        border: 1px dashed var(--line);
        border-radius: 10px;
        background: #fbfcff;
    }

    .wishlist-dropdown-footer {
        display: flex;
        justify-content: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--line);
    }

    .wishlist-dropdown-all {
        font-size: 13px;
        color: var(--primary);
        font-weight: 800;
    }

    .mini-cart {
        position: fixed;
        inset: 0;
        z-index: 110;
        pointer-events: none;
    }

    .mini-cart.is-open { pointer-events: auto; }

    .mini-cart-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 26, 42, 0.52);
        opacity: 0;
        transition: .2s ease;
    }

    .mini-cart.is-open .mini-cart-backdrop { opacity: 1; }

    .mini-cart-panel {
        position: absolute;
        top: 0;
        right: 0;
        width: min(430px, 92vw);
        height: 100%;
        background: #fff;
        border-inline-start: 1px solid var(--line);
        display: grid;
        grid-template-rows: auto 1fr auto;
        transform: translateX(100%);
        transition: .24s ease;
        box-shadow: -10px 0 30px rgba(23, 39, 59, .14);
    }

    .mini-cart.is-open .mini-cart-panel { transform: translateX(0); }
    [dir="rtl"] .mini-cart-panel { right: auto; left: 0; border-inline-start: 0; border-inline-end: 1px solid var(--line); transform: translateX(-100%); }
    [dir="rtl"] .mini-cart.is-open .mini-cart-panel { transform: translateX(0); }

    .mini-cart-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 12px;
        border-bottom: 1px solid var(--line);
    }

    .mini-cart-head h3 { margin: 0; font-size: 17px; color: var(--secondary); }

    .mini-cart-close {
        border: 1px solid var(--line);
        border-radius: 8px;
        background: #fff;
        color: var(--secondary);
        padding: 6px 10px;
        cursor: pointer;
        font-family: inherit;
        font-weight: 700;
    }

    .mini-cart-list {
        overflow: auto;
        padding: 12px;
        display: grid;
        gap: 10px;
        align-content: start;
    }

    .mini-cart-item {
        display: grid;
        grid-template-columns: 70px 1fr auto;
        gap: 10px;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 9px;
        align-items: center;
    }

    .mini-cart-item img {
        width: 70px;
        height: 92px;
        object-fit: cover;
        border-radius: 9px;
        background: #f2f2f5;
    }

    .mini-cart-item h4 {
        margin: 0 0 4px;
        font-size: 13px;
        line-height: 1.35;
        color: var(--secondary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .mini-cart-meta { font-size: 12px; color: var(--muted); display: flex; gap: 6px; align-items: center; }
    .mini-cart-price { font-size: 12px; color: var(--primary); font-weight: 800; margin-top: 4px; }

    .mini-cart-remove {
        border: 1px solid rgba(var(--wf-main-rgb), .2);
        background: #fff;
        color: var(--primary);
        border-radius: 8px;
        min-width: 34px;
        min-height: 34px;
        font-weight: 800;
        cursor: pointer;
    }

    .mini-cart-empty { color: var(--muted); font-size: 14px; padding: 8px 0; text-align: center; }
    .mini-cart-loading { color: var(--muted); font-size: 13px; text-align: center; padding: 10px 0; }

    .mini-cart-foot { border-top: 1px solid var(--line); padding: 12px; display: grid; gap: 8px; }
    .mini-cart-subtotal { font-size: 13px; color: var(--secondary); display: flex; justify-content: space-between; }
    .mini-cart-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .mini-cart-actions a { min-height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; text-decoration: none; }
    .mini-cart-view { border: 1px solid var(--line); background: #fff; color: var(--secondary); }
    .mini-cart-checkout { background: var(--primary); color: #fff; }

    .auth-modal { position: fixed; inset: 0; z-index: 120; pointer-events: none; }
    .auth-modal.is-open { pointer-events: auto; }
    .auth-modal-backdrop { position: absolute; inset: 0; background: rgba(15, 26, 42, .54); opacity: 0; transition: .22s ease; }
    .auth-modal.is-open .auth-modal-backdrop { opacity: 1; }

    .auth-modal-panel {
        position: absolute;
        top: 50%;
        left: 50%;
        width: min(450px, 94vw);
        max-height: min(88vh, 760px);
        overflow: auto;
        transform: translate(-50%, calc(-50% + 16px));
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        box-shadow: 0 22px 50px rgba(23, 39, 59, .24);
        padding: 18px;
        transition: .22s ease;
        opacity: 0;
    }

    .auth-modal.is-open .auth-modal-panel { transform: translate(-50%, -50%); opacity: 1; }
    .auth-head { display: flex; align-items: start; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
    .auth-title-wrap h3 { margin: 0; font-size: 22px; line-height: 1.2; color: var(--secondary); }
    .auth-title-wrap p { margin: 7px 0 0; font-size: 13px; color: var(--muted); }
    .auth-close { border: 1px solid var(--line); border-radius: 10px; min-width: 36px; min-height: 36px; background: #fff; color: var(--secondary); font-size: 18px; cursor: pointer; }
    .auth-form { display: grid; gap: 10px; }
    .auth-field { display: grid; gap: 6px; }
    .auth-field label { font-size: 12px; font-weight: 800; color: var(--secondary); }
    .auth-field input[type="text"], .auth-field input[type="password"] { min-height: 44px; border: 1px solid var(--line); border-radius: 10px; padding: 0 12px; font-size: 14px; color: var(--secondary); outline: none; font-family: inherit; background: #fff; }
    .auth-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .auth-remember { display: inline-flex; align-items: center; gap: 8px; color: var(--secondary); font-size: 13px; font-weight: 700; }
    .auth-forgot { color: var(--primary); font-size: 13px; font-weight: 700; }
    .auth-submit { min-height: 44px; border: 0; border-radius: 10px; background: var(--primary); color: #fff; font-size: 14px; font-weight: 800; font-family: inherit; cursor: pointer; }
    .auth-divider { display: flex; align-items: center; gap: 10px; color: var(--muted); font-size: 12px; margin: 2px 0; }
    .auth-divider::before, .auth-divider::after { content: ""; flex: 1; height: 1px; background: var(--line); }
    .auth-google-wrap { display: grid; gap: 8px; }
    .auth-google-wrap .googlesitekit-sign-in-with-google__frontend-output-button { width: 100%; min-height: 44px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--line); border-radius: 10px; background: #fff; overflow: hidden; }
    .auth-google-fallback { min-height: 44px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 14px; font-weight: 800; border: 1px solid var(--line); color: var(--secondary); background: #fff; text-decoration: none; }
    .auth-register { min-height: 44px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 14px; font-weight: 800; border: 1px dashed rgba(var(--wf-main-rgb), .38); color: var(--primary); background: #fff8f9; text-decoration: none; }

    .header-cta {
        height: 40px;
        padding: 0 14px;
        border-radius: 10px;
        font-size: 13px;
    }

    .action-nav-toggle {
        display: none;
    }

    @media (max-width: 980px) {
        .main-header-inner {
            grid-template-columns: 1fr;
            padding: 12px 0;
        }

        .brand,
        .main-nav,
        .header-actions {
            justify-content: center;
            text-align: center;
        }

        .main-nav {
            overflow-x: auto;
            flex-wrap: nowrap;
            justify-content: flex-start;
            scrollbar-width: thin;
        }

        .main-nav a {
            white-space: nowrap;
        }

        .footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .topbar {
            background: transparent;
            border-bottom: 0;
            height: 0;
            overflow: visible;
        }

        .promo {
            font-size: 12px;
            line-height: 1.45;
            padding: 8px 12px;
        }

        .topbar-inner {
            justify-content: center;
            min-height: 0;
            padding: 0;
        }

        .topbar-left {
            display: none;
        }

        .topbar-right {
            width: 100%;
            justify-content: flex-end;
            gap: 8px;
        }

        .topbar-desktop-contact {
            display: none;
        }

        .topbar-mobile-social {
            display: inline-flex;
        }

        .topbar-mobile-lang {
            display: inline-flex;
        }

        .topbar-mobile-social {
            inset-inline-end: 10px;
            bottom: 12px;
        }

        .topbar-mobile-lang {
            inset-inline-end: 10px;
            bottom: 80px;
        }

        .categories-strip-inner {
            padding: 8px 0;
            gap: 6px;
        }

        .category-strip-group {
            gap: 5px;
            padding-inline-end: 5px;
        }

        .category-strip-chip {
            min-height: 32px;
            padding: 0 10px;
            font-size: 12px;
        }

        .category-strip-sub {
            min-height: 30px;
            padding: 0 9px;
            font-size: 11px;
        }

        .main-header-inner {
            grid-template-columns: 1fr auto;
            gap: 8px;
            min-height: auto;
            padding: 10px 0;
        }

        .brand {
            align-items: center;
            text-align: center;
        }

        .brand-logo {
            height: 44px;
            max-width: 240px;
        }

        .brand-tag {
            font-size: 11px;
        }

        .main-nav {
            grid-column: 1 / -1;
            margin-top: 4px;
            border-radius: 10px;
            padding: 5px;
            gap: 6px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x proximity;
            scrollbar-width: none;
            display: none;
            justify-content: flex-start;
        }

        .main-nav.is-open {
            display: flex;
        }

        .main-nav::-webkit-scrollbar {
            display: none;
        }

        .main-nav a {
            font-size: 12px;
            padding: 7px 10px;
            scroll-snap-align: start;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 22px 0 14px;
        }

        .footer-brand,
        .footer-col {
            padding: 12px;
        }

        .footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            padding: 10px 0 14px;
        }

        .footer-mini-nav {
            justify-content: flex-start;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
            padding-bottom: 12px;
        }

        .footer-mini-nav::-webkit-scrollbar {
            display: none;
        }

        .header-actions {
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: nowrap;
        }

        .action-nav-toggle {
            display: inline-flex;
        }

        .search-form {
            display: none;
        }

        .icon-btn {
            min-width: 34px;
            min-height: 34px;
            padding: 0 8px;
            font-size: 12px;
            border-radius: 8px;
        }

        .icon-btn .icon {
            font-size: 15px;
        }

        .action-wishlist,
        .action-sell {
            display: none;
        }

        .category-trigger {
            min-height: 34px;
            padding: 0 10px;
            font-size: 12px;
            border-radius: 8px;
        }

        .category-menu-panel {
            width: min(210px, 78vw);
        }

        .action-account,
        .action-cart,
        .action-categories {
            min-width: 46px;
            justify-content: center;
        }
    }

    @media (max-width: 390px) {
        .action-account,
        .action-cart {
            min-width: 42px;
            font-size: 11px;
        }

        .category-trigger span:last-child {
            display: none;
        }

        .brand-logo {
            height: 36px;
            max-width: 200px;
        }

        .main-nav a {
            font-size: 11px;
            padding: 6px 9px;
        }

        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
