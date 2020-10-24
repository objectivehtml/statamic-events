const requestInterval = function(callback,delay) {
    let dateNow=Date.now,
        requestAnimation=window.requestAnimationFrame,
        start=dateNow(),
        stop,
        intervalFunc=function() {
            dateNow()-start<delay||(start+=delay, callback());
            stop||requestAnimation(intervalFunc);
        };
    requestAnimation(intervalFunc);
    
    return () => stop = 1;
};

export default (el, {value}, vnode) => {
    const clear = requestInterval(() => {
        const e = new Event('frame');

        el.dispatchEvent(e);

        if(e.defaultPrevented) {
            clear();
        }
    }, value);
};