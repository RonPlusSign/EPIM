import Vue from "vue";
import VueRouter from "vue-router";
import Products from "../views/Products.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    redirect: { name: "products" },
  },
  {
    path: "/prodotti",
    name: "products",
    component: Products,
  },
  {
    path: "/categorie",
    name: "categories",
    component: () =>
      import(/* webpackChunkName: "product" */ "../views/Categories.vue"),
  },
  {
    path: "/marche",
    name: "brands",
    component: () =>
      import(/* webpackChunkName: "product" */ "../views/Brands.vue"),
  },
  {
    path: "/prodotti/:id",
    name: "productDetail",
    component: () =>
      import(/* webpackChunkName: "product" */ "../views/ProductDetail.vue"),
  },
  {
    path: "/profilo",
    name: "profile",
    component: () =>
      import(/* webpackChunkName: "user" */ "../views/user/UserProfile.vue"),
  },

  {
    path: "/carrello",
    name: "cart",
    component: () =>
      import(/* webpackChunkName: "user" */ "../views/user/UserCart.vue"),
  },

  {
    path: "/profilo/ordini",
    name: "orders",
    component: () =>
      import(/* webpackChunkName: "user" */ "../views/user/UserOrders.vue"),
  },

  {
    path: "/admin",
    name: "admin",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/admin/Admin.vue"),
  },

  {
    path: "/admin/ordini",
    name: "ordersAdmin",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/admin/OrdersAdmin.vue"),
  },

  {
    path: "/admin/prodotti",
    name: "productsAdmin",
    component: () =>
      import(
        /* webpackChunkName: "admin" */ "../views/admin/ProductsAdmin.vue"
      ),
  },

  {
    path: "/admin/prodotti/nuovo",
    name: "createProduct",
    component: () =>
      import(
        /* webpackChunkName: "admin" */ "../views/admin/CreateProduct.vue"
      ),
  },

  {
    path: "/admin/prodotti/:id",
    name: "editProduct",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/admin/EditProduct.vue"),
  },

  {
    path: "/admin/marche",
    name: "brandsAdmin",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/admin/BrandsAdmin.vue"),
  },

  {
    path: "/admin/categorie",
    name: "categoriesAdmin",
    component: () =>
      import(
        /* webpackChunkName: "admin" */ "../views/admin/CategoriesAdmin.vue"
      ),
  },

  {
    path: "/admin/utenti",
    name: "usersAdmin",
    component: () =>
      import(/* webpackChunkName: "admin" */ "../views/admin/UsersAdmin.vue"),
  },

  {
    // will match everything (for 404 errors, pages not found)
    path: "/404",
    alias: "*",
    component: () =>
      import(/* webpackChunkName: "error" */ "../views/Error.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
