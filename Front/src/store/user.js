import Api from "@/services/api";

export default {
  namespaced: true,
  state: {
    login: null,
    tabNum: null,
    role: null,
    roles: null,
    photo:
      "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAtCAYAAAA6GuKaAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAACcpJREFUeNrEmQtQlNcVx//ftw9YYNkHy0NYHmVXRIz4gBgCqRJEnRoJcbSN44TOdEymSdqO4/iM6TSmbRQTnUwf6Wgepca2g52kHYNiiwExBhMjKuIjPiCCC3aBZZfXsu/d3rOKAorst5DmzNxh2f3uub97v3PPPedcbtu2bRguPp8PHMf5G322Wq1ISEiA2WyGzWZDXFwcjEYjZDIZVCoVPmo0/SQyUlkcHaV+JFqjitaolTJlRLhYLBbB7fagZ8DqNpl7bF0mS1dXt/liX1/PwZWZmjKLxXKfPrVajfb2doSHh98df4hnuIghULxeL+pMIUs1KuXmzJRp83Zv+WFoulYDRVjIWF0kd1pk76BDd6XNVHyorvFPjYYrX5ks3p3LY7yVQhnEQmBPdEqLEqURu7a8+HTa4tk6hEhFggajiT2WlkAt1OFcPL+qoXn+/k9qrhk6ezYUJnorJhXa4/Hgi67Qmq0/e/bJokf1mAyhCRfNS6OWVnG66ZPffXDg2FKVpyCQvvy4q9slzbnOawf+9faGSQMeLaSX9NM4NB6N+zAR5efn3/fl0Cb4T6t3x8slK8teXb1QGiIR49sU0r8sZ4ZUKlevKf/scqhOwVUPbcDRG/GB0AR81IBd29c/v+mZnGkc/o8yKzWO0+n1T7z377Py1Ehf1ZAneyg0vZoqg2/bzo0vvJI/IwnfhXwvRoGpU/W5e47Uc6mRqOV5/sE2TavrcrlQ3eZNX7dm1WvzMxLxXQqNTxzEQ1zEdx80rbBUKsXcrOyzzz4xY8KDerx0MExMB3EQD3EN35zi4W7tZHdY7aEtRbJgBzl+1Yh/nm5B8397YbO7IWWbSymXonBmPFZkp0A59gE0prz1QpFs2bortY8pHfkikeieTdPS17Rzub/6ecmv0+LVghV39dnxUlkd3iw/hUvtveh3eTHIjnCL3YkWYy8qv2jCwVOtSJ6igD42UtjpJ+KRkKhNKfv0/NFUBWegTek3D7KZRG3i3xdmpgQFXLz9MA6fakaiPg56XQxiosKhUoYhijXtFCXSmX32wIeSnYdQfuobwWMQF/ERp9+m6cNxo2jB8ysWJQu3Wy9eLjuB66Z+zMhIgITtco/LAx/Z853mpcZWPSFWibjUeKx/pxqNhm7B4MRHnMTLy+VyRKmiSgtmCndvRy6049jpG5iqnwK30zNih48Wt8sNlTwUnDwMvyyvFzwW8REn8fIdHR3Iyc7MGu0LxxMC3Fd7FWEqOURcYG6CwOOnqNB43YgzLSZB4xEfcRIvf9aqWL0oe7pE6MwtVidutPVAyezW6wnct0nEPOzs+TMtwk2EOImXD5OF/ygjMUqwgn6HG3Ync2tiYW+IDmTyuAMOj+AxiZN4eY1GPScsRPBCg2ejc+yVBXt+cEFENMRJvHysRq0KZlCFTMKUiOFgqy1oL9DhwFH/4KJG4uXVKkVoMJ0jZVJMT41iuaMVvIgPcHU5NkkPIlj+mKuPDQqaeHm1PCzoQPnFhdPhHRiEnXkFPoD3LZaIcPNmF/LmJiGdnY5BQTNeXioJCZYZOez0W/WDTDRdvQUvs2+e58ZcYTGLQ9qNfVCw5367MivoMYmXd7ocE4rESldmY+HcFHx9oQX9NhdEbDXJXIaaiHkXH5tL040OuM19KNuwBFp1RNDjEa/Y3D/ovpPiByWhUjEOrF2I0sMx+LDyAjraXZCESv3m4rtzoHCsZaXH462SHGYWygktEvGKzZZe+0Sg/aEie+WvFs3Cc4/rUNlowNcsNB2wuVneJ4JGHoLCjHgsSI+blOSAeMUdJrOFfZZPhsJkTQReKpj+rWY0xCs2mcznBh2upGAOmNHSahpAa/cAzOyIH2T+O4yZjjpcihQ2maSoiAnrZ5wgXvGgzfqPy4bu4mx9cK+vx+bEx/WtqDzTimutZvTZHWyzeOBmmZCYZRpSZiKRoSFIS1ZjaVYyy2CSoWQ+PhhhnCBebt26dYieV+R8ZdWTgpf6w5PN+MPBc2jt7EN4ZBiUqnCEsINDxLwGuW2KVD0eLxxUiLRYYe0bRHJMJH5RPAc/ztUJht5RfszV9VWFlI+NjcWX9Y1nxqvqDBdTvx0r3q7GhnePoZ/5s7T0BCTEKfzmQK7ax3R5GSz9pf/pe/qdnqPnqR/1Jz1CaonESbx8f38/ui3dW2ou3AzMbpnNLnmtAp9fMkCfroVGHebPTLxe35hJAH0/lMHQ89SP+pMe0heIEB9xEi8vkUiwIM5z/P2Pj7aO1/FWzyCWv3EEnQ4n0qfFgyMQj7A4j56nftSf9JA+0jueEB9xEq8/0qEPhjbD6urGljE72VigU/L7GhhtdqQka+Bi3sEXZGHDXxhi/UkP6SO9pH8sIS7iI867xRqKDfKneE7u2Ft+3D5G59cPNuB8sxGpKTH+5HUyhPSQPtJL+h8kxENcxDdU07sbU1IhZH6sM3/jexW20R0rzhvw54pz0E3TUgo+uacF00d6ST+NM1qIh7iGCjUjoClxdDqdOHumfu6Bzy+N6NjLDguX3Y5e5rJ45tI4bnIKqf7CC9NHev362TjDhTiIh7iGJ953q6akgGaTIofpb7UXubS0qfnJ0bdj3sxENbIytDhy4hraTb1QqCJAF0HB5lr+segiidl2MzONUGYmZZuWYnnWvdLLZ5cN+M0f972+KJH/SCwWj1io+0q99KNOwdW+S/VhnS6Xyq4kuhg5nsnTo8U8iLPM/Vjt7JiOkEJCr43yRXDjgnLMaVNw5WI+99atHnR3WLD40VTsW1uIOUn3ynHHLt7E1t0f7F6cxG2lFQ6oqE4P6hSo2ltZHyqVq/Oo0H07xZJgeXYKZqbGwtBtRfM3nehiJ52brTitHCUB/J1kgLvT6Gj0shV1ur2w9NpgNPbA3mfDLF0s3nguFxufmunXe/eUrW7wle75684lyfzmIZMYDc097B6RKqlVNz05s2dlfvrmT4vD5bKRWU5dUycON7Sh/poRBhaOWp0uuJgfdvvueRcxJ2Jvg0O4VIJElmJlp8Xhqdla5OljRpYkbA5s2nvQ2nC+sXBxkuhLMtWx7hG5QC4/6YKy8pqtZu2aB99uuZgHuM5SqTa26v4Iz+G+XeDwwZ+xU6SnZXHJ1LhINoH7k+CK003w326lyQroInRSLj9p1o9H2wu2v/OXov1a7a6SpwtG3CMSSEaC0t8CFcrKqxqa4b9HbGvbUJjIVYhEgYWvAWfiZF/fj7FVxCgHKkr37F/6vkq9OfOR9HnL8jLHu7G95zoHHbjSZsKhukZ740W6sTXvXJ6hqEzxu7TAa/mCywcEn6dxVMpkPZWq3qtYX1oX1N347Ige2EQOCC18kvxPgAEAHhx+eU27VUMAAAAASUVORK5CYII=",
    isLoading: false,
    error: null
  },
  mutations: {
    SET_USER_INFO(state, userInfo) {
      state.login = userInfo.login;
      // state.tabNum = userInfo.tabNum;
      state.tabNum = userInfo.login;
      state.role = userInfo.role;
      state.roles = userInfo.roles;
    },
    SET_LOADING(state, isLoading) {
      state.isLoading = isLoading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    }
  },
  getters: {
    userPersonnelNumber: state => {
      return state.tabNum;
    },
    userRole: state => {
      return state.role;
    }
  },
  actions: {
    async loadInitialData({ commit }) {
      try {
        commit("SET_LOADING", true);
        const response = await Api.fetchUserData();
        commit("SET_USER_INFO", response.data);
      } catch (error) {
        commit("SET_ERROR", error);
      } finally {
        commit("SET_LOADING", false);
      }
    }
  }
};
