import HomePage from '../pages/home.vue';
import ProductPage from '../pages/product.vue';
import NotFoundPage from '../pages/404.vue';
import selectRecipe from "@/pages/SelectRecipe.vue";
import BookMarks from "@/pages/BookMarks.vue";
import sharedRecipe from "@/pages/SharedRecipe.vue";
import notifications from "@/pages/notifications.vue";
import profile from "@/pages/profile.vue";
import discover from "@/pages/discover.vue";

var routes = [
    {
        path: '/',
        component: HomePage,
    },
    {
        path: '/bookmarks/',
        component: BookMarks,
    },
    {
        path: '/product/:id/',
        component: ProductPage,
    },
    {
      path: '/recipe/:id',
      component: selectRecipe
    },
    {
      path: '/profile',
      component: profile
    },
    {
      path:"/discover",
      component: discover
    },
    {
      path: '/notifications',
      component: notifications
    },

    {
      path: "/shared-recipe/:ulid",
      component: sharedRecipe
    },
    {
        path: '(.*)',
        component: NotFoundPage,
    },
];

export default routes;
