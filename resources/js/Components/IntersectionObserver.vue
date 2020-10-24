<script>
export default {
    props: {
        observe: String,
        
        rootMargin: String,
        
        threshold: [Array, Number]
    },
    data: () => ({
        observers: []
    }),
    mounted() {
        this.$el.querySelectorAll(this.observe).forEach(el => {
            const observer = new IntersectionObserver(this.onIntersect, {
                rootMargin: this.rootMargin,
                threshold: this.threshold
            });

            observer.observe(el);

            this.observers.push(observer);
        });
    },
    methods: {
        onIntersect(entries) {
            entries.forEach(entry => {
                const event = new Event('intersect');

                Object.assign(event, {
                    entry 
                });

                entry.target.dispatchEvent(event);
            });
        }
    },
    render(h) {
        return h('div', this.$slots.default);
    }
};
</script>