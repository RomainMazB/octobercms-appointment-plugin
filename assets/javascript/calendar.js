let Calendar = require('./calendar.vue')
new Vue({
    el: '#calendar',
    // delimiters: ['{(', ')}'],
    components: { Calendar },
    render: h => h(App)
  })
