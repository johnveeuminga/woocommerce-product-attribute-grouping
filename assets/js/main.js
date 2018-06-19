import Vue from 'vue/dist/vue.js';
import ProductAttributeGroup from './components/ProductAttributeGroup.vue';
import '../scss/style.scss';
// import '../css/style.css';

Vue.component( 'product-attribute-group', ProductAttributeGroup );
const app = new Vue({
	el: '#app'
});