export default (el, binding, vnode) => {
    el.addEventListener('click', e => {
        e.preventDefault();

        const target = el.ownerDocument.querySelector(
            el.getAttribute('data-target')
        );

        if(target) {
            (binding.value || 'show').split(' ').forEach(value => {
                target.classList.toggle(value);
            });
        }
    });
};