import Vue from 'vue'
import VueRouter from 'vue-router'
import Products from '../views/Products.vue'
import ProductDetail from '../views/ProductDetail.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: { name: 'Products' }  // Better if default is "Categories"
  },
  {
    path: '/prodotti',
    name: 'Products',
    component: Products
  },
  {
    path: '/prodotti/:id',
    name: 'ProductDetail',
    component: ProductDetail
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
