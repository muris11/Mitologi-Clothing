@props(['disabled' => false, 'rows' => 8])

@php
    $editorId = $attributes->get('id') ?: 'trix-' . \Illuminate\Support\Str::uuid();
    $fieldName = $attributes->get('name');
    $fieldValue = $attributes->get('value') ?? '';
@endphp

<div
    data-rich-text-wrapper
    data-rich-text-name="{{ $fieldName }}"
    {{ $attributes->except(['id', 'name', 'value', 'class'])->merge(['class' => 'rounded-xl shadow-sm']) }}
>
    <textarea
        id="{{ $editorId }}_fallback"
        name="{{ $fieldName }}"
        rows="{{ $rows }}"
        @disabled($disabled)
        class="block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-xl shadow-sm focus:border-mitologi-gold focus:ring-mitologi-gold"
    >{{ $fieldValue }}</textarea>

    <input id="{{ $editorId }}" type="hidden" value="{{ $fieldValue }}" @disabled($disabled)>

    <trix-editor
        input="{{ $editorId }}"
        class="trix-content border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-xl focus:border-mitologi-gold focus:ring-mitologi-gold shadow-sm p-4 hidden"
        style="min-height: 300px;"
    ></trix-editor>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const hasTrix = typeof customElements !== 'undefined' && customElements.get('trix-editor');
                if (!hasTrix) return;

                document.querySelectorAll('[data-rich-text-wrapper]').forEach((wrapper) => {
                    const name = wrapper.dataset.richTextName;
                    const fallback = wrapper.querySelector('textarea');
                    const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                    const editor = wrapper.querySelector('trix-editor');

                    if (!name || !fallback || !hiddenInput || !editor) return;

                    hiddenInput.setAttribute('name', name);
                    fallback.removeAttribute('name');
                    fallback.classList.add('hidden');
                    editor.classList.remove('hidden');
                });
            });
        </script>
    @endpush
@endonce

<style>
    trix-toolbar {
        padding: 0.5rem;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-bottom: none;
        border-radius: 0.75rem 0.75rem 0 0;
    }
    .dark trix-toolbar {
        background-color: #1f2937;
        border-color: #374151;
    }
    trix-toolbar .trix-button-group {
        border-color: #e5e7eb;
        border-radius: 0.5rem;
        margin-bottom: 0;
        overflow: hidden;
    }
    .dark trix-toolbar .trix-button-group {
        border-color: #374151;
    }
    trix-toolbar .trix-button {
        border-bottom: none;
        padding: 0.4rem 0.6rem;
    }
    trix-toolbar .trix-button:hover {
        background-color: #f3f4f6;
    }
    .dark trix-toolbar .trix-button:hover {
        background-color: #374151;
    }
    trix-toolbar .trix-button.trix-active {
        background-color: rgba(197, 160, 89, 0.15);
    }
    .dark trix-toolbar .trix-button.trix-active {
        background-color: rgba(197, 160, 89, 0.25);
    }
    trix-editor {
        border: 1px solid #d1d5db;
        border-radius: 0 0 0.75rem 0.75rem;
        min-height: 300px;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    .dark trix-editor {
        border: 1px solid #374151;
    }
    trix-editor:focus {
        border-color: #C5A059;
        box-shadow: 0 0 0 1px #C5A059;
        outline: none;
    }
    trix-editor h1 { font-size: 1.5rem; font-weight: 700; margin: 0.75rem 0; }
    trix-editor ul, trix-editor ol { padding-left: 1.5rem; }
    trix-editor blockquote { border-left: 3px solid #C5A059; padding-left: 1rem; color: #6b7280; font-style: italic; }
    trix-editor a { color: #C5A059; text-decoration: underline; }
    trix-editor pre { background-color: #f3f4f6; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; }
    .dark trix-editor pre { background-color: #1f2937; }
</style>
