import Api from "./services/api";

class Logger {
  log(message, retry = 3) {
    try {
      Api.log(message);
    } catch (error) {
      if (retry > 0) {
        setTimeout(() => {
          this.log(message, retry - 1);
        }, 1000);
      }
    }
  }
}

export default new Logger();
