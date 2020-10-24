<script>
import TextInput from './TextInput';
import SelectInput from './SelectInput';
import SvgIcon from './SvgIcon';

// import Calendar from 'v-calendar/lib/components/calendar.umd'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd';

// Register components in your 'main.js'
// Vue.component('calendar', Calendar)

export default {
    components: {
        VDatePicker,
        TextInput,
        SelectInput,
        SvgIcon
    },
    props: {
        name: String,
        isReadOnly: Boolean,
        value: [String, Number]
    },
    computed: {
        $config() {
            return {
                get: () => {}
            };
        },
        containerWidth() {
            return null;
        }
    },
    methods: {
        update() {
            // Do nothing...
        }
    }
};
</script>

<style lang="scss">
/* ==========================================================================
   DATE & TIME FIELDTYPES
   ========================================================================== */

.daterange {
	outline: none;
	width: 100%;
	.icon-remove { right: 32px; }

    .dr-calendar { max-width: 250px; }

    .dr-active, .dr-input {
        z-index: 1;
    }
}

.datetime .icon-remove, .time-fieldtype .icon-remove {
	cursor: pointer;
	background: rgba(0, 0, 0, 0.1);
	font-size: 15px;
	line-height: 1;
	padding: 0px 4px 2px;
	border-radius: 50%;
	color: #777;
    height: 18px;
    width: 18px;
    margin-right: 8px;
	text-shadow: 0 1px 1px #FFF;
}

.daterange--single .dr-input .dr-dates {
    min-width: 60px;
}

.date-time-container {
    display: flex;

	.time-container {
        margin-left: .25rem;
	}
}

// Make date and time and fields sit one above the other
// when it's too narrow for them to sit side by side.
.date-time-container.narrow {
    display: block;

    .date-container {
        margin-bottom: .25rem;
    }

    .time-container {
        margin-left: 0;
    }
}

// Dear sweet baby Jesus please forgive me for this brittle selector
.date-time-container .date-container > span > .vc-popover-content-wrapper {
	left: -50px !important;
}

/* Time Field
  ========================================================================== */

.time-fieldtype {
	.time-fieldtype-container {
        display: flex;
        align-items: center;
		max-width: 140px;
	}

	.input-time {
        display: inline-block;
        text-align: center;
        background-color: transparent;
		-moz-appearance: textfield;
		width: 26px;

		&:active, &:focus {
			outline: none;
		}
	}

	.colon {
		// @apply select-none;
		margin: 0 -2px;
	}

	input::-webkit-inner-spin-button,
	input::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
}


/* Inside a Grid field
  ========================================================================== */

.grid-mode-table .date-fieldtype {
    .daterange .icon {
        top: 8px;
    }

    .btn.add-time { top: 8px; }

    .dr-input { border: 0 !important; }

    .colon {
        background: #fff;
        margin: 0;
        padding: 8px 5px;
    }
}
</style>