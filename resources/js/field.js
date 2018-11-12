Nova.booting((Vue, router) => {
    Vue.component('index-nova-gmap', require('./components/IndexField'));
    Vue.component('detail-nova-gmap', require('./components/DetailField'));
    Vue.component('form-nova-gmap', require('./components/FormField'));
})
