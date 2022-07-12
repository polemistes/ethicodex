require('./bootstrap');

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Shared/Layout";

createInertiaApp({
  resolve: name => {
    let page = require(`./Pages/${name}`).default;
   
    page.layout = Layout;
    
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component("Link", Link)
      .mount(el)
  },
})

InertiaProgress.init({
    delay: 10,
    showSpinner: true,
    color: '#660',
    includeCSS: true,
})