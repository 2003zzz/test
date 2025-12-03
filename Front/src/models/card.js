export default class Card {
  constructor(data = {}) {
    this.id_v01 = data.id_v01 || null;
    this.workshop = data.workshop || "";
    this.party = data.party || "";
    this.service_number = data.service_number;
    this.number_technological_notification =
      data.number_technological_notification || "";
    // TODO: Delete TD as it's double for another fields
    if (data.cipher_main_td) {
      this.cipher_main_TD =
        data.cipher_main_td + " / " + (data.type_technical_doc || "");
    }
    this.cipher_main_td = data.cipher_main_td || "";
    this.type_technical_doc = data.type_technical_doc || "";
    this.note = data.note || "";
    this.designation = data.designation || "";
    this.code_detail = data.code_detail || "";
    this.id_status = data.id_status || null;
    this.id_version = data.id_version || null;
    this.date_of_create = data.date_of_create || "";
    this.notification_number_ott = data.notification_number_ott || "";
    this.create_service_number = data.create_service_number || "";
    this.number_of_parts_in_batch = data.number_of_parts_in_batch || "";
    this.validity_period_norms = data.validity_period_norms || "";
    this.minimum_number_blanks = data.minimum_number_blanks || "";
    this.id_e05 = data.id_e05 || null;

    this.laboriousness_controloper_dse_wshs =
      data.laboriousness_controloper_dse_wshs || "";
    this.laboriousness_controloper_dse_wshs_kzo =
      data.laboriousness_controloper_dse_wshs_kzo || "";
    this.laboriousness_controloperations_dse_workshop =
      data.laboriousness_controloperations_dse_workshop || "";
    this.laboriousness_controloperations_dse_workshop_kzo =
      data.laboriousness_controloperations_dse_workshop_kzo || "";
    this.laboriousness_on_dse = data.laboriousness_on_dse || "";
    this.laboriousness_on_dse_controloper_wshs =
      data.laboriousness_on_dse_controloper_wshs || "";
    this.laboriousness_on_dse_controloper_wshs_kzo =
      data.laboriousness_on_dse_controloper_wshs_kzo || "";
    this.laboriousness_on_dse_kzo = data.laboriousness_on_dse_kzo || "";
    this.total_laboriousness_electroperations =
      data.total_laboriousness_electroperations || "";
    this.total_laboriousness_workshop = data.total_laboriousness_workshop || "";
    this.total_laboriousness_workshop_kzo =
      data.total_laboriousness_workshop_kzo || "";
  }
}
