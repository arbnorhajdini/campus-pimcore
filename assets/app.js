// Entry point of the frontend. Every module enhances markup that already works without JavaScript.
import { initFocusOnLoad } from './js/focus-on-load.js';
import { initNavigation } from './js/navigation.js';
import { initRegistrationForm } from './js/registration-form.js';

initNavigation();
initRegistrationForm();
initFocusOnLoad();
