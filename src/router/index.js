import { createRouter, createWebHashHistory } from "vue-router";
import TestView from "../views/TestView.vue";

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: "/",
      name: "Test",
      component: TestView,
    },
    // {
    //   path: "/path",
    //   name: "Path Name",
    //   component: () => import("../views/PathView.vue"),
    // },
  ],
});

export default router;
