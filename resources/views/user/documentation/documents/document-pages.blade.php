@extends('user.layout.layout')

@section('title', $title ?? '🟢🟢🟢')
@section('css')

    <style>
        .pm-root {
            --pm-primary: #FF8600;
            --pm-primary-soft: rgba(255, 134, 0, 0.10);
            --pm-primary-border: rgba(255, 134, 0, 0.30);
            --pm-success: #29aa85;
            --pm-success-soft: rgba(41, 170, 133, 0.10);
            --pm-success-border: rgba(41, 170, 133, 0.30);
            --pm-warning: #eb9d59;
            --pm-warning-soft: rgba(235, 157, 89, 0.12);
            --pm-danger: #ec8290;
            --pm-danger-soft: rgba(236, 130, 144, 0.12);
            --pm-info: #62B7E5;
            --pm-info-soft: rgba(98, 183, 229, 0.12);
            --pm-purple: #8c57d1;
            --pm-purple-soft: rgba(140, 87, 209, 0.12);
            --pm-teal: #29aa85;
            --pm-teal-soft: rgba(91, 231, 189, 0.12);

            --pm-body: #4a5a6b;
            --pm-heading: #001b2f;
            --pm-muted: #6c757d;
            --pm-dim: #adb5bd;

            --pm-bg: #ffffff;
            --pm-surface: #ffffff;
            --pm-surface2: #f8f9fa;

            --pm-border: #dee2e6;
            --pm-border2: #ced4da;

            --pm-radius: 0.5rem;
            --pm-radius-sm: 0.325rem;

            --pm-shadow-sm: 0 1px 4px rgba(0, 27, 47, 0.06), 0 0 0 1px rgba(0, 27, 47, 0.04);
            --pm-shadow: 0 4px 16px rgba(0, 27, 47, 0.10);

            --pm-font-body: 'Plus Jakarta Sans', sans-serif;
            --pm-font-display: 'Libre Baskerville', serif;
            --pm-font-size: 0.8125rem;


            font-size: var(--pm-font-size);
            color: var(--pm-body);
            background: var(--pm-bg);
            border-radius: var(--pm-radius);
            overflow: hidden;
            box-shadow: var(--pm-shadow);
            border: 1px solid var(--pm-border);
        }

        /* ---------- SHELL ---------- */
        .pm-root .pm-shell {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: 600px;
            max-height: 88vh;
        }

        /* ---------- SIDEBAR ---------- */
        .pm-root .pm-sidebar {
            background: var(--pm-surface);
            border-right: 1px solid var(--pm-border);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .pm-root .pm-sidebar-inner {
            flex: 1;
            padding: 20px 0 12px;
        }

        .pm-root .pm-brand {

            font-size: 1.05rem;
            font-weight: 700;
            color: var(--pm-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 16px 18px;
            letter-spacing: -0.3px;
        }

        .pm-root .pm-brand-icon {
            width: 28px;
            height: 28px;
            background: var(--pm-primary-soft);
            border: 1px solid var(--pm-primary-border);
            color: var(--pm-primary);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pm-root .pm-sidebar-section {
            margin-bottom: 20px;
        }

        .pm-root .pm-sidebar-label {
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--pm-dim);
            padding: 0 16px 6px;
            display: block;
        }

        .pm-root .pm-nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            width: 100%;
            padding: 7px 16px;
            background: transparent;
            border: none;
            border-left: 2px solid transparent;
            color: var(--pm-muted);

            font-size: 0.8rem;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: all 0.14s ease;
        }

        .pm-root .pm-nav-item:hover {
            background: var(--pm-surface2);
            color: var(--pm-heading);
        }

        .pm-root .pm-nav-item.active {
            border-left-color: var(--pm-primary);
            color: var(--pm-primary);
            background: var(--pm-primary-soft);
        }

        .pm-root .pm-count {
            margin-left: auto;
            font-size: 0.6rem;
            font-weight: 600;
            background: var(--pm-surface2);
            color: var(--pm-muted);
            border: 1px solid var(--pm-border);
            padding: 1px 6px;
            border-radius: 20px;
        }

        .pm-root .pm-nav-item.active .pm-count {
            background: var(--pm-primary-soft);
            border-color: var(--pm-primary-border);
            color: var(--pm-primary);
        }

        .pm-root .pm-sidebar-footer {
            padding: 14px 14px 16px;
            border-top: 1px solid var(--pm-border);
        }

        /* ---------- MAIN ---------- */
        .pm-root .pm-main {
            background: var(--pm-bg);
            padding: 24px 28px;
            overflow-y: auto;
        }

        /* ---------- PAGE HEADER ---------- */
        .pm-root .pm-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .pm-root .pm-page-title {

            font-size: 1.35rem;
            font-weight: 700;
            color: var(--pm-heading);
            margin: 0 0 3px;
            line-height: 1.2;
        }

        .pm-root .pm-page-subtitle {
            color: var(--pm-muted);
            font-size: 0.775rem;
            margin: 0;
        }

        /* ---------- STAT CARDS ---------- */
        .pm-root .pm-stat-card {
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius);
            padding: 14px 16px;
            box-shadow: var(--pm-shadow-sm);
        }

        .pm-root .pm-stat-val {

            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pm-heading);
            line-height: 1;
            margin-bottom: 3px;
        }

        .pm-root .pm-stat-val.pm-val-live {
            color: var(--pm-success);
        }

        .pm-root .pm-stat-val.pm-val-draft {
            color: var(--pm-warning);
        }

        .pm-root .pm-stat-val.pm-val-off {
            color: var(--pm-danger);
        }

        .pm-root .pm-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--pm-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* ---------- CONTROLS ---------- */
        .pm-root .pm-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .pm-root .pm-search {
            flex: 1;
            min-width: 180px;
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            padding: 7px 12px;

            font-size: 0.8rem;
            color: var(--pm-heading);
            outline: none;
            transition: border-color 0.15s;
        }

        .pm-root .pm-search:focus {
            border-color: var(--pm-primary);
            box-shadow: 0 0 0 3px var(--pm-primary-soft);
        }

        .pm-root .pm-search::placeholder {
            color: var(--pm-dim);
        }

        .pm-root .pm-filter-group {
            display: flex;
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            overflow: hidden;
            background: var(--pm-surface);
        }

        .pm-root .pm-filter-btn {
            padding: 6px 13px;

            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            border-right: 1px solid var(--pm-border);
            background: transparent;
            color: var(--pm-muted);
            cursor: pointer;
            transition: all 0.14s;
        }

        .pm-root .pm-filter-btn:last-child {
            border-right: none;
        }

        .pm-root .pm-filter-btn:hover {
            background: var(--pm-surface2);
            color: var(--pm-heading);
        }

        .pm-root .pm-filter-btn.active {
            background: var(--pm-primary);
            color: #fff;
        }

        .pm-root .pm-select {
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            padding: 6px 10px;

            font-size: 0.775rem;
            color: var(--pm-body);
            cursor: pointer;
            outline: none;
        }

        .pm-root .pm-select:focus {
            border-color: var(--pm-primary);
        }

        /* ---------- PAGE CARDS ---------- */
        .pm-root .pm-page-card {
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius);
            padding: 13px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 8px;
            transition: all 0.14s;
            box-shadow: var(--pm-shadow-sm);
        }

        .pm-root .pm-page-card:hover {
            border-color: var(--pm-border2);
            box-shadow: var(--pm-shadow);
            transform: translateY(-1px);
        }

        .pm-root .pm-status-bar {
            width: 3px;
            height: 38px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .pm-root .pm-card-body {
            flex: 1;
            min-width: 0;
        }

        .pm-root .pm-card-top {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 3px;
        }

        .pm-root .pm-page-name {
            font-weight: 700;
            font-size: 0.825rem;
            color: var(--pm-heading);
        }

        /* type badges */
        .pm-root .pm-badge {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 2px 7px;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .pm-root .pm-badge-privacy {
            background: var(--pm-info-soft);
            color: var(--pm-info);
            border-color: rgba(98, 183, 229, 0.3);
        }

        .pm-root .pm-badge-terms {
            background: var(--pm-warning-soft);
            color: #c27a32;
            border-color: rgba(235, 157, 89, 0.3);
        }

        .pm-root .pm-badge-conduct {
            background: var(--pm-purple-soft);
            color: var(--pm-purple);
            border-color: rgba(140, 87, 209, 0.3);
        }

        .pm-root .pm-badge-cookie {
            background: var(--pm-teal-soft);
            color: var(--pm-teal);
            border-color: rgba(41, 170, 133, 0.3);
        }

        .pm-root .pm-badge-custom {
            background: var(--pm-primary-soft);
            color: var(--pm-primary);
            border-color: var(--pm-primary-border);
        }

        /* version tags */
        .pm-root .pm-ver-tag {
            font-size: 0.6rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            background: var(--pm-surface2);
            border: 1px solid var(--pm-border);
            color: var(--pm-muted);
        }

        .pm-root .pm-ver-tag.pm-ver-all {
            background: var(--pm-primary-soft);
            border-color: var(--pm-primary-border);
            color: var(--pm-primary);
        }

        /* status pill */
        .pm-root .pm-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.675rem;
            font-weight: 600;
            color: var(--pm-muted);
        }

        .pm-root .pm-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .pm-root .pm-status-dot.live {
            background: var(--pm-success);
            box-shadow: 0 0 0 3px var(--pm-success-soft);
        }

        .pm-root .pm-status-dot.draft {
            background: var(--pm-warning);
        }

        .pm-root .pm-status-dot.off {
            background: var(--pm-dim);
        }

        .pm-root .pm-card-slug {
            font-size: 0.7rem;
            color: var(--pm-dim);

        }

        /* card actions */
        .pm-root .pm-card-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        /* Toggle switch */
        .pm-root .pm-toggle {
            width: 34px;
            height: 19px;
            border-radius: 10px;
            background: var(--pm-border2);
            position: relative;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            flex-shrink: 0;
        }

        .pm-root .pm-toggle.on {
            background: var(--pm-success);
        }

        .pm-root .pm-toggle::after {
            content: '';
            position: absolute;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #fff;
            top: 3px;
            left: 3px;
            transition: transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .pm-root .pm-toggle.on::after {
            transform: translateX(15px);
        }

        /* Icon buttons */
        .pm-root .pm-icon-btn {
            width: 28px;
            height: 28px;
            border-radius: var(--pm-radius-sm);
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            color: var(--pm-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.14s;
        }

        .pm-root .pm-icon-btn:hover {
            background: var(--pm-surface2);
            color: var(--pm-heading);
            border-color: var(--pm-border2);
        }

        .pm-root .pm-icon-btn.pm-danger:hover {
            background: var(--pm-danger-soft);
            color: var(--pm-danger);
            border-color: rgba(236, 130, 144, 0.4);
        }

        /* ---------- VERSIONS VIEW ---------- */
        .pm-root .pm-version-card {
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 10px;
            box-shadow: var(--pm-shadow-sm);
            transition: all 0.14s;
        }

        .pm-root .pm-version-card:hover {
            box-shadow: var(--pm-shadow);
        }

        .pm-root .pm-ver-num {

            font-size: 1.4rem;
            font-weight: 700;
            color: var(--pm-primary);
            min-width: 64px;
        }

        .pm-root .pm-ver-meta {
            flex: 1;
        }

        .pm-root .pm-ver-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--pm-heading);
            margin-bottom: 2px;
        }

        .pm-root .pm-ver-sub {
            font-size: 0.725rem;
            color: var(--pm-muted);
        }

        .pm-root .pm-ver-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            background: var(--pm-surface2);
            border: 1px solid var(--pm-border);
            color: var(--pm-muted);
            white-space: nowrap;
        }

        .pm-root .pm-ver-badge.pm-current {
            background: var(--pm-primary-soft);
            border-color: var(--pm-primary-border);
            color: var(--pm-primary);
        }

        /* ---------- BUTTONS ---------- */
        .pm-root .pm-btn-primary {
            background: var(--pm-primary);
            color: #fff;

            font-size: 0.8rem;
            font-weight: 600;
            border: none;
            border-radius: var(--pm-radius-sm);
            padding: 7px 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .pm-root .pm-btn-primary:hover {
            background: #e07600;
            color: #fff;
        }

        .pm-root .pm-btn-outline {
            background: transparent;
            color: var(--pm-body);

            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            padding: 7px 14px;
            transition: all 0.14s;
            white-space: nowrap;
        }

        .pm-root .pm-btn-outline:hover {
            border-color: var(--pm-border2);
            background: var(--pm-surface2);
        }

        .pm-root .pm-btn-sm {
            padding: 5px 10px;
            font-size: 0.725rem;
        }

        /* ---------- MODAL ---------- */
        .pm-root .pm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 27, 47, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .pm-root .pm-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .pm-root .pm-modal {
            background: var(--pm-surface);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius);
            width: 640px;
            max-width: 96vw;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 27, 47, 0.18);
            transform: translateY(10px);
            transition: transform 0.2s;
            overflow: hidden;
        }

        .pm-root .pm-modal-sm {
            width: 440px;
        }

        .pm-root .pm-overlay.show .pm-modal {
            transform: translateY(0);
        }

        .pm-root .pm-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid var(--pm-border);
        }

        .pm-root .pm-modal-title {

            font-size: 1rem;
            font-weight: 700;
            color: var(--pm-heading);
            margin: 0;
        }

        .pm-root .pm-close-btn {
            width: 28px;
            height: 28px;
            border-radius: var(--pm-radius-sm);
            background: transparent;
            border: 1px solid var(--pm-border);
            color: var(--pm-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.14s;
        }

        .pm-root .pm-close-btn:hover {
            background: var(--pm-surface2);
            color: var(--pm-heading);
        }

        .pm-root .pm-modal-body {
            padding: 22px;
            overflow-y: auto;
            flex: 1;
        }

        .pm-root .pm-modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--pm-border);
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            background: var(--pm-surface2);
        }

        /* ---------- FORM ELEMENTS ---------- */
        .pm-root .pm-label {
            display: block;
            font-size: 0.675rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--pm-muted);
            margin-bottom: 5px;
        }

        .pm-root .pm-input {
            width: 100%;
            background: var(--pm-bg);
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            padding: 8px 11px;

            font-size: 0.8rem;
            color: var(--pm-heading);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            display: block;
        }

        .pm-root .pm-input:focus {
            border-color: var(--pm-primary);
            box-shadow: 0 0 0 3px var(--pm-primary-soft);
        }

        .pm-root .pm-textarea {
            resize: vertical;
            min-height: 72px;
        }

        .pm-root .pm-hint {
            font-size: 0.7rem;
            color: var(--pm-dim);
            margin-top: 4px;
            margin-bottom: 0;
        }

        /* Scope option cards */
        .pm-root .pm-scope-card {
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.14s;
            height: 100%;
        }

        .pm-root .pm-scope-card:hover {
            border-color: var(--pm-border2);
            background: var(--pm-surface2);
        }

        .pm-root .pm-scope-card.selected {
            border-color: var(--pm-primary);
            background: var(--pm-primary-soft);
        }

        .pm-root .pm-scope-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--pm-heading);
            margin-bottom: 3px;
        }

        .pm-root .pm-scope-desc {
            font-size: 0.7rem;
            color: var(--pm-muted);
        }

        /* Version checkboxes */
        .pm-root .pm-ver-checks {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-top: 8px;
        }

        .pm-root .pm-ver-check {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 6px 9px;
            border: 1px solid var(--pm-border);
            border-radius: var(--pm-radius-sm);
            cursor: pointer;
            font-size: 0.75rem;
            color: var(--pm-muted);
            font-weight: 600;
            transition: all 0.14s;
        }

        .pm-root .pm-ver-check:hover {
            border-color: var(--pm-border2);
            color: var(--pm-heading);
        }

        .pm-root .pm-ver-check.checked {
            border-color: var(--pm-primary);
            background: var(--pm-primary-soft);
            color: var(--pm-primary);
        }

        .pm-root .pm-check-box {
            width: 14px;
            height: 14px;
            border: 1.5px solid var(--pm-border2);
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            flex-shrink: 0;
            transition: all 0.14s;
        }

        .pm-root .pm-ver-check.checked .pm-check-box {
            background: var(--pm-primary);
            border-color: var(--pm-primary);
            color: #fff;
        }

        /* ---------- EMPTY STATE ---------- */
        .pm-root .pm-empty {
            text-align: center;
            padding: 52px 20px;
        }

        .pm-root .pm-empty-icon {
            font-size: 2.2rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .pm-root .pm-empty-title {

            font-size: 1rem;
            color: var(--pm-muted);
            margin-bottom: 5px;
        }

        .pm-root .pm-empty-desc {
            font-size: 0.775rem;
            color: var(--pm-dim);
        }

        /* ---------- TOAST ---------- */
        .pm-root .pm-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--pm-heading);
            color: #fff;
            padding: 10px 18px;
            border-radius: var(--pm-radius);
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2000;
            opacity: 0;
            transform: translateY(8px);
            transition: all 0.25s;
            pointer-events: none;
            box-shadow: var(--pm-shadow);
        }

        .pm-root .pm-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* ---------- UTILS ---------- */
        .pm-root .pm-hidden {
            display: none !important;
        }

        /* ---------- RELEASE PILLS (sidebar, read-only from $releases) ---------- */
        .pm-root .pm-release-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 6px 16px;
            font-size: 0.775rem;
            font-weight: 500;
            color: var(--pm-muted);
        }

        .pm-root .pm-release-pill.pm-release-current {
            color: var(--pm-heading);
            font-weight: 600;
        }

        .pm-root .pm-release-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pm-root .pm-release-dot.published {
            background: var(--pm-success);
            box-shadow: 0 0 0 2px var(--pm-success-soft);
        }

        .pm-root .pm-release-dot.unpublished {
            background: var(--pm-dim);
        }

        .pm-root .pm-release-name {
            flex: 1;
        }

        .pm-root .pm-release-badge {
            font-size: 0.575rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 1px 6px;
            border-radius: 20px;
            background: var(--pm-primary-soft);
            border: 1px solid var(--pm-primary-border);
            color: var(--pm-primary);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 640px) {
            .pm-root .pm-shell {
                grid-template-columns: 1fr;
            }

            .pm-root .pm-sidebar {
                border-right: none;
                border-bottom: 1px solid var(--pm-border);
                max-height: 180px;
            }

            .pm-root .pm-sidebar-inner {
                padding: 12px 0 8px;
            }

            .pm-root .pm-sidebar-footer {
                display: flex;
                gap: 8px;
            }

            .pm-root .pm-sidebar-footer .btn {
                flex: 1;
            }

            .pm-root .pm-main {
                padding: 16px;
            }

            .pm-root .pm-ver-checks {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">{{ $title ?? '' }}</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="{{ authRoute('user.profile.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ authRoute('user.documentation.index') }}">Documentations</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ authRoute('user.documentation.show.latest', ['documentation' => $documentation]) }}">{{ $documentation->title }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <x-alert-component />

                <div class="row">
                    <div class="col-12">
                        <div class="card overflow-hidden">
                            {{-- <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2 widget-icons-sections">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26px" height="26px"
                                            viewBox="0 0 80 80">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="5">
                                                <path
                                                    d="M4 27h12.343a4 4 0 0 0 2.829-1.172l3.485-3.485A8 8 0 0 1 28.314 20h4.372a8 8 0 0 1 5.27 1.98a8 8 0 0 0-.28.257l-6.806 6.555a5.954 5.954 0 1 0 8.34 8.498L41.5 35l15.964 12.417a2.653 2.653 0 0 1 .51 3.663l-1.608 2.194A7.9 7.9 0 0 1 50 56.5l-1.113 1.113a6.44 6.44 0 0 1-8.678.394L39 57l-.702.702a7.846 7.846 0 0 1-11.096 0l-7.53-7.53A4 4 0 0 0 16.843 49H4z" />
                                                <path
                                                    d="M46 30.5L41.5 35m0 0l-2.29 2.29a5.954 5.954 0 1 1-8.34-8.498l6.807-6.555A8 8 0 0 1 43.226 20h8.46a8 8 0 0 1 5.657 2.343l3.485 3.485A4 4 0 0 0 63.658 27H76v22H59.5zM12 27.059v22m56-22v22" />
                                            </g>
                                        </svg>
                                    </div>
                                    <h5 class="card-title mb-0">Other pages</h5>

                                    <div class="ms-auto fw-semibold d-flex gap-1">
                                        <a href="{{ authRoute('user.documentation.partners.create', ['documentation' => $documentation]) }}"
                                            class="btn btn-light btn-sm border center-content gap-1">
                                            <i class="bx bx-plus fs-5"></i>
                                            <div>Add Page</div>
                                        </a>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="card-body p-0">

                                {{-- Pass releases to JS as a JSON array --}}
                                <script>
                                    window.pmReleasesData = @json($formattedVersion);
                                </script>
                                <!-- POLICY MANAGER COMPONENT -->
                                <div class="pm-root" id="policyManager">

                                    <!-- SIDEBAR + MAIN LAYOUT -->
                                    <div class="pm-shell">

                                        <!-- SIDEBAR -->
                                        <aside class="pm-sidebar">
                                            <div class="pm-sidebar-inner">
                                                <div class="pm-brand">
                                                    <span class="pm-brand-icon">
                                                        <svg width="16" height="16" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                                                        </svg>
                                                    </span>
                                                    Other Pages
                                                </div>

                                                <div class="pm-sidebar-section">
                                                    <p class="pm-sidebar-label">Pages</p>
                                                    <button class="pm-nav-item active" data-view="all">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <rect x="2" y="2" width="12" height="12"
                                                                rx="1.5" />
                                                            <path d="M5 5h6M5 8h6M5 11h4" />
                                                        </svg>
                                                        All Pages
                                                        <span class="pm-count" id="pm-count-all">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="privacy">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <path d="M8 2L3 4v4c0 3 2.5 5 5 6 2.5-1 5-3 5-6V4L8 2z" />
                                                        </svg>
                                                        Privacy
                                                        <span class="pm-count" id="pm-count-privacy">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="terms">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <rect x="3" y="1" width="10" height="14"
                                                                rx="1" />
                                                            <path d="M5.5 5h5M5.5 8h5M5.5 11h3" />
                                                        </svg>
                                                        Terms
                                                        <span class="pm-count" id="pm-count-terms">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="conduct">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <circle cx="8" cy="5" r="2.5" />
                                                            <path d="M3 13c0-2.76 2.24-5 5-5s5 2.24 5 5" />
                                                        </svg>
                                                        Code of Conduct
                                                        <span class="pm-count" id="pm-count-conduct">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="cookie">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <circle cx="8" cy="8" r="6" />
                                                            <circle cx="6" cy="7" r="1"
                                                                fill="currentColor" />
                                                            <circle cx="10" cy="6" r="1"
                                                                fill="currentColor" />
                                                            <circle cx="9" cy="10" r="1"
                                                                fill="currentColor" />
                                                        </svg>
                                                        Cookie Policy
                                                        <span class="pm-count" id="pm-count-cookie">0</span>
                                                    </button>
                                                    <button class="pm-nav-item" data-view="custom">
                                                        <svg width="14" height="14" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                                            <path d="M8 3v10M3 8h10" />
                                                        </svg>
                                                        Custom
                                                        <span class="pm-count" id="pm-count-custom">0</span>
                                                    </button>
                                                </div>

                                                {{-- Versions section: read-only list from $releases --}}
                                                <div class="pm-sidebar-section">
                                                    <p class="pm-sidebar-label">Releases</p>
                                                    @foreach ($releases as $release)
                                                        <div
                                                            class="pm-release-pill {{ $release->is_current ? 'pm-release-current' : '' }}">
                                                            <span
                                                                class="pm-release-dot {{ $release->is_published ? 'published' : 'unpublished' }}"></span>
                                                            <span class="pm-release-name">{{ $release->version }}</span>
                                                            @if ($release->is_current)
                                                                <span class="pm-release-badge">current</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="pm-sidebar-footer">
                                                <button class="btn pm-btn-primary w-100"
                                                    onclick="pmOpenModal('pm-page-modal')">
                                                    + New Page
                                                </button>
                                            </div>
                                        </aside>

                                        <!-- MAIN CONTENT -->
                                        <main class="pm-main">

                                            <!-- PAGES VIEW -->
                                            <div id="pm-view-pages">
                                                <div class="pm-page-header">
                                                    <div>
                                                        <h2 class="pm-page-title" id="pm-view-title">All Pages</h2>
                                                        <p class="pm-page-subtitle" id="pm-view-subtitle">Manage all
                                                            policy and legal pages across versions</p>
                                                    </div>
                                                    <button class="btn pm-btn-primary"
                                                        onclick="pmOpenModal('pm-page-modal')">
                                                        <svg width="13" height="13" viewBox="0 0 16 16"
                                                            fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <path d="M8 3v10M3 8h10" />
                                                        </svg>
                                                        New Page
                                                    </button>
                                                </div>

                                                <!-- STATS -->
                                                <div class="row g-3 mb-4">
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val" id="pm-stat-total">0</div>
                                                            <div class="pm-stat-label">Total Pages</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-live" id="pm-stat-live">0</div>
                                                            <div class="pm-stat-label">Live</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-draft" id="pm-stat-draft">0
                                                            </div>
                                                            <div class="pm-stat-label">Draft</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="pm-stat-card">
                                                            <div class="pm-stat-val pm-val-off" id="pm-stat-off">0</div>
                                                            <div class="pm-stat-label">Off</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- CONTROLS -->
                                                <div class="pm-controls">
                                                    <input class="pm-search" type="text" placeholder="Search pages…"
                                                        id="pm-search" oninput="pmRenderPages()">
                                                    <div class="pm-filter-group">
                                                        <button class="pm-filter-btn" id="pmf-all"
                                                            onclick="pmSetFilter('all')">All</button>
                                                        <button class="pm-filter-btn active" id="pmf-live"
                                                            onclick="pmSetFilter('live')">Live</button>
                                                        <button class="pm-filter-btn" id="pmf-draft"
                                                            onclick="pmSetFilter('draft')">Draft</button>
                                                        <button class="pm-filter-btn" id="pmf-off"
                                                            onclick="pmSetFilter('off')">Off</button>
                                                    </div>
                                                    <select class="pm-select" id="pm-ver-filter"
                                                        onchange="pmSetVersionFilter(this.value)">
                                                        <option value="">All Releases</option>
                                                        @foreach ($releases as $release)
                                                            <option value="{{ $release->version }}">
                                                                {{ $release->version }} — {{ $release->title }}
                                                                {{ $release->is_current ? '(current)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- LIST -->
                                                <div id="pm-pages-list"></div>
                                            </div>

                                        </main>
                                    </div>

                                    <!-- TOAST -->
                                    <div class="pm-toast" id="pm-toast"></div>

                                    <!-- PAGE MODAL -->
                                    <div class="pm-overlay" id="pm-page-modal">
                                        <div class="pm-modal">
                                            <div class="pm-modal-header">
                                                <h5 class="pm-modal-title" id="pm-page-modal-title">Create New Page</h5>
                                                <button class="pm-close-btn" onclick="pmCloseModal('pm-page-modal')">
                                                    <svg width="14" height="14" viewBox="0 0 16 16"
                                                        fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <path d="M3 3l10 10M13 3L3 13" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="pm-modal-body">
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="pm-label">Page Name</label>
                                                        <input type="text" class="pm-input" id="pm-page-name"
                                                            placeholder="e.g. Privacy Policy">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="pm-label">Page Type</label>
                                                        <select class="pm-input" id="pm-page-type">
                                                            <option value="privacy">Privacy Policy</option>
                                                            <option value="terms">Terms of Service</option>
                                                            <option value="conduct">Code of Conduct</option>
                                                            <option value="cookie">Cookie Policy</option>
                                                            <option value="custom">Custom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="pm-label">URL Slug</label>
                                                    <input type="text" class="pm-input" id="pm-page-slug"
                                                        placeholder="e.g. /privacy-policy">
                                                    <p class="pm-hint">The URL path where this page will be accessible</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="pm-label">Release Scope</label>
                                                    <div class="row g-2 mt-1">
                                                        <div class="col-6">
                                                            <div class="pm-scope-card selected" id="pm-scope-all"
                                                                onclick="pmSelectScope('all')">
                                                                <div class="pm-scope-title">All Releases</div>
                                                                <div class="pm-scope-desc">Show this page for every release
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="pm-scope-card" id="pm-scope-specific"
                                                                onclick="pmSelectScope('specific')">
                                                                <div class="pm-scope-title">Specific Releases</div>
                                                                <div class="pm-scope-desc">Show only for selected releases
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 pm-hidden" id="pm-version-picker">
                                                    <label class="pm-label">Select Releases</label>
                                                    <div class="pm-ver-checks" id="pm-ver-checkboxes"></div>
                                                    <p class="pm-hint">Page will only appear for the selected releases</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="pm-label">Status</label>
                                                    <select class="pm-input" id="pm-page-status">
                                                        <option value="draft">Draft — not visible</option>
                                                        <option value="live">Live — publicly visible</option>
                                                        <option value="off">Off — disabled</option>
                                                    </select>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="pm-label">Notes / Preview</label>
                                                    <textarea class="pm-input pm-textarea" id="pm-page-content"
                                                        placeholder="Add a short description or content excerpt…"></textarea>
                                                </div>
                                            </div>
                                            <div class="pm-modal-footer">
                                                <button class="btn pm-btn-outline"
                                                    onclick="pmCloseModal('pm-page-modal')">Cancel</button>
                                                <button class="btn pm-btn-primary" onclick="pmSavePage()">Save
                                                    Page</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{-- END POLICY MANAGER COMPONENT --}}

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- End content -->

        @include('layout.components.copyright')
    </div>
@endsection

@section('js')
    <script>
        (function() {
            'use strict';

            // ─── VERSIONS: sourced from Laravel $releases, injected above ────────
            // pmReleasesData is set in the inline <script> block before .pm-root
            const pmVersions = window.pmReleasesData || [];

            // ─── STATE ────────────────────────────────────────────────────────────
            let pmPages = [{
                    id: 1,
                    name: 'Privacy Policy',
                    type: 'privacy',
                    slug: '/privacy',
                    scope: 'all',
                    versions: [],
                    status: 'live',
                    content: 'This Privacy Policy explains how we collect, use, and protect your personal data.'
                },
                {
                    id: 2,
                    name: 'Privacy Policy v2',
                    type: 'privacy',
                    slug: '/privacy-v2',
                    scope: 'specific',
                    versions: ['v2.0.0', 'v3.0.0'],
                    status: 'live',
                    content: 'Updated privacy policy with GDPR compliance and new data retention schedules.'
                },
                {
                    id: 3,
                    name: 'Terms of Service',
                    type: 'terms',
                    slug: '/terms',
                    scope: 'all',
                    versions: [],
                    status: 'live',
                    content: 'By using our service, you agree to these terms.'
                },
                {
                    id: 4,
                    name: 'Code of Conduct',
                    type: 'conduct',
                    slug: '/conduct',
                    scope: 'all',
                    versions: [],
                    status: 'draft',
                    content: 'Our community code of conduct sets expectations for respectful interaction.'
                },
                {
                    id: 5,
                    name: 'Cookie Policy',
                    type: 'cookie',
                    slug: '/cookies',
                    scope: 'specific',
                    versions: ['v1.0.0'],
                    status: 'live',
                    content: 'We use cookies to improve your experience.'
                },
            ];

            let pmCurrentView = 'all';
            let pmCurrentFilter = 'live';
            let pmVerFilter = '';
            let pmEditingId = null;
            let pmScopeMode = 'all';
            let pmSelectedVers = new Set();

            // ─── UTILS ────────────────────────────────────────────────────────────
            function pmGenId() {
                return Date.now() + Math.random();
            }

            function pmEl(id) {
                return document.getElementById(id);
            }

            function pmNotify(msg) {
                const t = pmEl('pm-toast');
                if (!t) return;
                t.textContent = msg;
                t.classList.add('show');
                setTimeout(() => t.classList.remove('show'), 2200);
            }

            // ─── EXPOSE GLOBALS for inline onclick ───────────────────────────────
            window.pmOpenModal = pmOpenModal;
            window.pmCloseModal = pmCloseModal;
            window.pmSavePage = pmSavePage;
            window.pmSelectScope = pmSelectScope;
            window.pmSetFilter = pmSetFilter;
            window.pmSetVersionFilter = pmSetVersionFilter;
            window.pmRenderPages = pmRenderPages;
            window.pmTogglePage = pmTogglePage;
            window.pmDeletePage = pmDeletePage;
            window.pmEditPage = pmEditPage;
            window.pmToggleVerCheck = pmToggleVerCheck;

            // ─── NAV ─────────────────────────────────────────────────────────────
            document.querySelectorAll('.pm-nav-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.dataset.view;
                    if (!view) return;
                    document.querySelectorAll('.pm-nav-item').forEach(b => b.classList.remove(
                        'active'));
                    this.classList.add('active');
                    pmCurrentView = view;
                    pmEl('pm-view-pages').classList.remove('pm-hidden');
                    pmUpdateHeader(view);
                    pmRenderPages();
                });
            });

            const pmViewMeta = {
                all: ['All Pages', 'Manage all policy and legal pages across releases'],
                privacy: ['Privacy Policy', 'Privacy and data protection pages'],
                terms: ['Terms of Service', 'Terms and legal agreement pages'],
                conduct: ['Code of Conduct', 'Community conduct pages'],
                cookie: ['Cookie Policy', 'Cookie and tracking policy pages'],
                custom: ['Custom Pages', 'Custom policy pages'],
            };

            function pmUpdateHeader(view) {
                const [title, sub] = pmViewMeta[view] || pmViewMeta.all;
                pmEl('pm-view-title').textContent = title;
                pmEl('pm-view-subtitle').textContent = sub;
            }

            // ─── PAGES ────────────────────────────────────────────────────────────
            function pmGetFiltered() {
                let result = pmPages;
                if (pmCurrentView !== 'all') result = result.filter(p => p.type === pmCurrentView);
                const q = (pmEl('pm-search')?.value || '').toLowerCase();
                if (q) result = result.filter(p =>
                    p.name.toLowerCase().includes(q) || p.slug.toLowerCase().includes(q)
                );
                if (pmCurrentFilter && pmCurrentFilter !== 'all')
                    result = result.filter(p => p.status === pmCurrentFilter);
                if (pmVerFilter)
                    result = result.filter(p => p.scope === 'all' || p.versions.includes(pmVerFilter));
                return result;
            }

            function pmRenderPages() {
                pmUpdateStats();
                pmUpdateCounts();

                const list = pmEl('pm-pages-list');
                const filtered = pmGetFiltered();

                if (!filtered.length) {
                    list.innerHTML = `
                <div class="pm-empty">
                    <div class="pm-empty-icon">📄</div>
                    <div class="pm-empty-title">No pages found</div>
                    <div class="pm-empty-desc">Create a new page or adjust your filters</div>
                </div>`;
                    return;
                }

                list.innerHTML = filtered.map(p => {
                    const vTags = p.scope === 'all' ?
                        `<span class="pm-ver-tag pm-ver-all">All Releases</span>` :
                        p.versions.map(v => `<span class="pm-ver-tag">${v}</span>`).join('');

                    const barColor = p.status === 'live' ?
                        'var(--pm-success)' :
                        p.status === 'draft' ?
                        'var(--pm-warning)' :
                        'var(--pm-dim)';

                    return `
                <div class="pm-page-card">
                    <div class="pm-status-bar" style="background:${barColor}"></div>
                    <div class="pm-card-body">
                        <div class="pm-card-top">
                            <span class="pm-page-name">${p.name}</span>
                            <span class="pm-badge pm-badge-${p.type}">${p.type}</span>
                            ${vTags}
                            <span class="pm-status-pill">
                                <span class="pm-status-dot ${p.status}"></span>
                                ${p.status.charAt(0).toUpperCase() + p.status.slice(1)}
                            </span>
                        </div>
                        <div class="pm-card-slug">${p.slug}</div>
                    </div>
                    <div class="pm-card-actions">
                        <button class="pm-toggle ${p.status === 'live' ? 'on' : ''}"
                            onclick="pmTogglePage(${p.id}, event)"
                            title="${p.status === 'live' ? 'Disable' : 'Enable'}"></button>
                        <button class="pm-icon-btn" onclick="pmEditPage(${p.id})" title="Edit">
                            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 2l3 3-9 9H2v-3L11 2z"/>
                            </svg>
                        </button>
                        <button class="pm-icon-btn pm-danger" onclick="pmDeletePage(${p.id})" title="Delete">
                            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 4h12M6 4V2h4v2M5 4l1 10h4l1-10"/>
                            </svg>
                        </button>
                    </div>
                </div>`;
                }).join('');
            }

            function pmUpdateStats() {
                const base = pmCurrentView === 'all' ? pmPages : pmPages.filter(p => p.type === pmCurrentView);
                pmEl('pm-stat-total').textContent = base.length;
                pmEl('pm-stat-live').textContent = base.filter(p => p.status === 'live').length;
                pmEl('pm-stat-draft').textContent = base.filter(p => p.status === 'draft').length;
                pmEl('pm-stat-off').textContent = base.filter(p => p.status === 'off').length;
            }

            function pmUpdateCounts() {
                pmEl('pm-count-all').textContent = pmPages.length;
                ['privacy', 'terms', 'conduct', 'cookie', 'custom'].forEach(t => {
                    pmEl('pm-count-' + t).textContent = pmPages.filter(p => p.type === t).length;
                });
            }

            function pmSetFilter(f) {
                pmCurrentFilter = pmCurrentFilter === f ? '' : f;
                document.querySelectorAll('.pm-filter-btn').forEach(b => b.classList.remove('active'));
                if (pmCurrentFilter) pmEl('pmf-' + pmCurrentFilter)?.classList.add('active');
                pmRenderPages();
            }

            function pmSetVersionFilter(v) {
                pmVerFilter = v;
                pmRenderPages();
            }

            function pmTogglePage(id, e) {
                e.stopPropagation();
                const p = pmPages.find(x => x.id === id);
                if (!p) return;
                p.status = p.status === 'live' ? 'off' : 'live';
                pmRenderPages();
                pmNotify(`"${p.name}" is now ${p.status}`);
            }

            function pmDeletePage(id) {
                const p = pmPages.find(x => x.id === id);
                if (!p || !confirm(`Delete "${p.name}"?`)) return;
                pmPages = pmPages.filter(x => x.id !== id);
                pmRenderPages();
                pmNotify('Page deleted');
            }

            function pmEditPage(id) {
                const p = pmPages.find(x => x.id === id);
                if (!p) return;
                pmEditingId = id;
                pmEl('pm-page-modal-title').textContent = 'Edit Page';
                pmEl('pm-page-name').value = p.name;
                pmEl('pm-page-type').value = p.type;
                pmEl('pm-page-slug').value = p.slug;
                pmEl('pm-page-status').value = p.status;
                pmEl('pm-page-content').value = p.content;
                pmSelectedVers = new Set(p.versions);
                pmSelectScope(p.scope);
                pmRefreshVerChecks();
                pmOpenModal('pm-page-modal');
            }

            // ─── MODAL ────────────────────────────────────────────────────────────
            function pmOpenModal(id) {
                if (id === 'pm-page-modal' && pmEditingId === null) {
                    pmResetPageForm();
                    pmEl('pm-page-modal-title').textContent = 'Create New Page';
                }
                pmRefreshVerChecks();
                pmEl(id)?.classList.add('show');
            }

            function pmCloseModal(id) {
                pmEl(id)?.classList.remove('show');
                pmEditingId = null;
            }

            function pmResetPageForm() {
                ['pm-page-name', 'pm-page-slug', 'pm-page-content'].forEach(id => pmEl(id).value = '');
                pmEl('pm-page-type').value = 'privacy';
                pmEl('pm-page-status').value = 'draft';
                pmSelectedVers = new Set();
                pmSelectScope('all');
            }

            function pmSelectScope(mode) {
                pmScopeMode = mode;
                pmEl('pm-scope-all').classList.toggle('selected', mode === 'all');
                pmEl('pm-scope-specific').classList.toggle('selected', mode === 'specific');
                pmEl('pm-version-picker').classList.toggle('pm-hidden', mode === 'all');
            }

            function pmRefreshVerChecks() {
                const container = pmEl('pm-ver-checkboxes');
                if (!container) return;
                container.innerHTML = pmVersions.map(v => `
            <div class="pm-ver-check ${pmSelectedVers.has(v.name) ? 'checked' : ''}"
                onclick="pmToggleVerCheck('${v.name}', this)">
                <div class="pm-check-box">${pmSelectedVers.has(v.name) ? '✓' : ''}</div>
                ${v.name}${v.current ? ' ★' : ''}
            </div>
        `).join('');
            }

            function pmToggleVerCheck(name, el) {
                if (pmSelectedVers.has(name)) {
                    pmSelectedVers.delete(name);
                    el.classList.remove('checked');
                    el.querySelector('.pm-check-box').textContent = '';
                } else {
                    pmSelectedVers.add(name);
                    el.classList.add('checked');
                    el.querySelector('.pm-check-box').textContent = '✓';
                }
            }

            function pmSavePage() {
                const name = pmEl('pm-page-name').value.trim();
                if (!name) {
                    pmNotify('Please enter a page name');
                    return;
                }
                const type = pmEl('pm-page-type').value;
                const slug = pmEl('pm-page-slug').value.trim() || '/' + name.toLowerCase().replace(/\s+/g, '-');
                const status = pmEl('pm-page-status').value;
                const content = pmEl('pm-page-content').value.trim();

                if (pmEditingId !== null) {
                    const p = pmPages.find(x => x.id === pmEditingId);
                    if (p) Object.assign(p, {
                        name,
                        type,
                        slug,
                        status,
                        content,
                        scope: pmScopeMode,
                        versions: [...pmSelectedVers]
                    });
                    pmNotify(`"${name}" updated`);
                } else {
                    pmPages.push({
                        id: pmGenId(),
                        name,
                        type,
                        slug,
                        status,
                        content,
                        scope: pmScopeMode,
                        versions: [...pmSelectedVers]
                    });
                    pmNotify(`"${name}" created`);
                }

                pmCloseModal('pm-page-modal');
                pmRenderPages();
            }

            // Close modal when clicking the backdrop
            document.querySelectorAll('.pm-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) pmCloseModal(this.id);
                });
            });

            // ─── INIT ─────────────────────────────────────────────────────────────
            pmRenderPages();

        })();
    </script>
@endsection
