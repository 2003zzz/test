import axios from "axios";
axios.defaults.withCredentials = true;

export default class Api {
  // Card
  static async fetchCardById(cardId) {
    return await axios.get(`cards/${cardId}`);
  }
  static async createCard(cardCreateDto) {
    return await axios.post(`cards`, { ...cardCreateDto });
  }
  static async saveCard(cardModel) {
    return await axios.put(`cards/`, cardModel);
  }
  static async deleteCard(cardId) {
    return await axios.delete(`cards/${cardId}`);
  }
  // Products
  static async fetchProductCards(productDto) {
    return await axios.post("cards/show", {
      item: productDto
    });
  }
  // Archive
  static async fetchArchiveCardById(cardId, versionId) {
    return await axios.get(`cards/${cardId}/archive/${versionId}`);
  }
  static async fetchListArchiveCards(cardId) {
    return await axios.get(`cards/${cardId}/versions`);
  }
  // Status
  static async fetchListStatuses() {
    return await axios.get(`status`);
  }
  static async selectCardStatus(cardId, statusId) {
    return await axios.put(`cards/${cardId}/status/${statusId}`);
  }
  // Search
  static async searchCards(query, params) {
    return await axios.post(`search/cards`, query, params);
  }
  static async searchProducts(query, params) {
    return await axios.post("search/product", query, params);
  }
  static async searchHardware(query) {
    return await axios.post(
      `search/hardware`,
      {},
      {
        params: {
          query
        }
      }
    );
  }
  static async searchOperations(query) {
    return await axios.post(
      `search/operation`,
      {},
      {
        params: {
          query
        }
      }
    );
  }
  static async searchProfessions(query) {
    return await axios.post(
      `search/profession`,
      {},
      {
        params: {
          query
        }
      }
    );
  }
  // Operations
  static async deleteOperation(operationId) {
    return await axios.delete(`operations/${operationId}`);
  }
  static async duplicateOperationToCards(operation, cardIds) {
    return await axios.post(`operations/duplicate`, {
      operation,
      cardIds
    });
  }
  // Documents
  static async fetchDocumentByCardId(cardId) {
    return await axios.get(`documents/${cardId}`, {
      responseType: "arraybuffer"
    });
  }
  static async createCardFromDocument(documentFormData) {
    return await axios.post("documents", documentFormData, {
      headers: {
        "Content-Type": "multipart/form-data"
      }
    });
  }
  // Logs
  static async log(message) {
    return await axios.post("log", { message });
  }
  static async fetchLogsByTabNum(tabNum) {
    return await axios.post("search/logs", {
      tabNum
    });
  }
  // User
  static async fetchUserData() {
    return await axios.get("/auth");
  }
}
