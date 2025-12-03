import Vue from 'vue';
import Vuex from 'vuex';

import userModule from './user';
import cardModule from './card';
import operationModule from './operation';
import searchModule from './search';
import archiveModule from './archive';
import statusModule from './status';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    user: userModule,
    card: cardModule,
    operation: operationModule,
    search: searchModule,
    archive: archiveModule,
    status: statusModule,
  },
});