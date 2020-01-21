
require('./bootstrap');

window.Vue = require('vue');

Vue.component('todo-component', require('./components/Todo.vue').default);

window.Fire = new Vue();

const app = new Vue({
    el: '#app',
});
