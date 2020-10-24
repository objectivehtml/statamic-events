import RRule from './Components/Fieldtypes/RRule.vue';
import ImportEvent from './Components/Widgets/ImportEvents.vue';

Statamic.booting(() => {
    Statamic.$components.register('rrule-fieldtype', RRule);
    Statamic.$components.register('ImportEvents', ImportEvent);
});