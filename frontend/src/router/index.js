import Vue from 'vue'
import VueRouter from 'vue-router'
import Products from '../views/Products.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: { name: 'products' }  // Better if default is "Categories"
  },
  {
    path: '/prodotti',
    name: 'products',
    component: Products
  },
  {
    path: '/prodotti/:id',
    name: 'productDetail',
    component: () => import( /* webpackChunkName: "product" */ "../views/ProductDetail.vue")
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
