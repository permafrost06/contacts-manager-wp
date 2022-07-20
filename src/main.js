import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";

const app = createApp(App).use(createPinia());

app.use(router);

app.mount("#app");
