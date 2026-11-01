/**
 * Narrows the location select to the locations of the chosen course. Every course option carries its
 * location ids in data-locations. Without JavaScript all locations stay selectable and the server validates.
 */
export function initRegistrationForm() {
    const course = document.querySelector('[data-course-select]');
    const location = document.querySelector('[data-location-select]');
    if (!course || !location) {
        return;
    }

    const update = () => {
        const ids = course.selectedOptions[0]?.dataset.locations?.split(',') ?? null;

        for (const option of location.options) {
            if (option.value === '') {
                continue;
            }
            const offered = ids === null || ids.includes(option.value);
            option.disabled = !offered;
            option.hidden = !offered;
        }

        if (location.selectedOptions[0]?.disabled) {
            location.value = '';
        }
        if (ids?.length === 1) {
            location.value = ids[0];
        }
    };

    course.addEventListener('change', update);
    update();
}
