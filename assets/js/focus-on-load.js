/**
 * Moves the focus to a message that appears after a page load, like the errors of a submitted form.
 * Keyboard and screen reader users then start right there instead of at the top of the page.
 */
export function initFocusOnLoad() {
    document.querySelector('[data-focus-on-load]')?.focus();
}
