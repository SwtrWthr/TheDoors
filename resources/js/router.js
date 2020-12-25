import Vue from 'vue';
import VueRouter from "vue-router";

Vue.use(VueRouter);
import Home from "./views/Home";
import Login from "./views/Login";
import Profile from "./views/Profile";
import Cart from "./views/Cart";
import Admin from "./views/Admin";
import DoorView from "./views/DoorView";

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/cart',
    name: 'cart',
    component: Cart
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile
  },
  {
    path: '/adminka',
    name: 'adminka',
    component: Admin,
    meta: {
      noContainer: true
    }
  },
  {
    path: '/door/:id',
    name: 'DoorView',
    component: DoorView
  }
]

export default new VueRouter({
  mode: 'history',
  routes
})
