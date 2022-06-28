import { createRouter, createWebHashHistory } from "vue-router";
import TableView from "../views/TableView.vue";

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: "/",
      name: "Table",
      component: TableView,
    },
    // {
    //   path: "/path",
    //   name: "Path Name",
    //   component: () => import("../views/PathView.vue"),
    // },
  ],
});

export default router;
