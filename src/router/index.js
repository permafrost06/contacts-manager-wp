import { createRouter, createWebHashHistory } from "vue-router";
import TableView from "../views/TableView.vue";

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: "/",
      name: "Contacts Table",
      component: TableView,
    },
    {
      path: "/add-new",
      name: "Add New Contact",
      component: () => import("../views/AddNewView.vue"),
    },
    {
      path: "/edit/:id",
      name: "Edit Contact",
      component: () => import("../views/EditView.vue"),
      props: true,
    },
  ],
});

export default router;
