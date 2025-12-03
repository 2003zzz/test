export default class Operation {
  static UID = 1;
  static getUID() {
    return Operation.UID++;
  }
  constructor(data = {}) {
    this.id_v01 = data.id_v01 || null;
    this.id_v02 = data.id_v02 || null;
    this.time_rate_is_paid = data.time_rate_is_paid || "";
    this.end_to_end_operation_number = data.end_to_end_operation_number || null;
    this.operation_number = data.operation_number || null;
    this.norm_of_cycle_time = data.norm_of_cycle_time || "";
    this.launch_ratio = data.launch_ratio || "";
    this.cipher_of_the_reference_tp = data.cipher_of_the_reference_tp || "";
    this.cipher_of_the_operation = data.cipher_of_the_operation
      ? {
          p014: data.cipher_of_the_operation.p014,
          p018: data.cipher_of_the_operation.p018,
        }
      : {};
    this.hardware_cipher = data.hardware_cipher
      ? {
          p451: data.hardware_cipher.p451,
          p501: data.hardware_cipher.p501,
        }
      : {};
    this.cipher_of_the_profession = data.cipher_of_the_profession
      ? {
          profcode70: data.cipher_of_the_profession.profcode70,
          snm_as_scaption: data.cipher_of_the_profession.snm_as_scaption,
          type_of_profession_reference_book:
            data.cipher_of_the_profession.type_of_profession_reference_book,
        }
      : {};
    this.operation_as_needed = data.operation_as_needed || "";
    this.operations_for_samples = data.operations_for_samples || "";
    this.number_of_worker = data.number_of_worker || "";
    this.operation_with_technological_shutdowns =
      data.operation_with_technological_shutdowns || "";
    this.operation_for_execution = data.operation_for_execution || "";
    this.code_of_the_tariff_grid = data.code_of_the_tariff_grid || "";
    this.category_of_work = data.category_of_work || "";
    this.number_notification_sgt = data.number_notification_sgt || "";
    this.type_of_norms = data.type_of_norms || "";
    this.unit_of_the_rationong = data.unit_of_the_rationong || "";
    // При наличии редактора операций, некоторые операции могут не иметь id_v02 до сохранения КНВ
    // Для корректного распознавания элементов списками v-for во Vue необходимо иметь свойство с уникальным значением для каждой операции
    this.UID = Operation.getUID();
  }

  static createOperationForPush(data) {
    return new Operation({
      ...data,
      time_rate_is_paid:
        (data.time_rate_is_paid || "") +
        (data.unit_of_measurement === "ч"
          ? " " + data.unit_of_measurement
          : ""),
      cipher_of_the_operation:
        data.p014 || data.cipher_of_the_operation
          ? {
              p014: data.p014,
              p018: data.cipher_of_the_operation,
            }
          : {},
      hardware_cipher:
        data.p451 || data.hardware_cipher
          ? {
              p451: data.p451,
              p501: data.hardware_cipher,
            }
          : {},
      cipher_of_the_profession:
        data.cipher_of_the_profession || data.snm_as_scaption
          ? {
              profcode70: data.cipher_of_the_profession,
              snm_as_scaption: data.snm_as_scaption,
              type_of_profession_reference_book:
                data.type_of_profession_reference_book,
            }
          : {},
    });
  }

  static toRequestFormat(data) {
    const [time, unit] = data.time_rate_is_paid.trim().split(" ");
    const hardware_cipher =
      data.hardware_cipher &&
      data.hardware_cipher.p501 &&
      data.hardware_cipher.p501.replaceAll(".", "");

    // Удаление внутреннего поля, не относящегося напрямую к модели
    delete data.UID;

    return {
      ...data,
      cipher_of_the_operation: data.cipher_of_the_operation.p018 || "",
      cipher_of_the_profession: data.cipher_of_the_profession.profcode70 || "",
      type_of_profession_reference_book:
        data.cipher_of_the_profession.type_of_profession_reference_book || "",
      hardware_cipher: hardware_cipher || "",
      time_rate_is_paid: time,
      unit_of_measurement: unit || "м",
    };
  }

  countMoreDataFields() {
    const moreDataFields = [
      this.operation_as_needed,
      this.operations_for_samples,
      this.number_of_worker,
      this.operation_with_technological_shutdowns,
      this.operation_for_execution,
    ];
    return moreDataFields.filter((field) => !!field).length;
  }
  get MoreDataShortString() {
    if (this.countMoreDataFields() !== 1) {
      return "";
    }
    if (this.operation_as_needed) {
      return `${this.operation_as_needed}`;
    }
    if (this.operations_for_samples) {
      return `${this.operations_for_samples}`;
    }
    if (this.number_of_worker) {
      return `на ${this.number_of_worker} х.`;
    }
    if (this.operation_with_technological_shutdowns) {
      return `${this.operation_with_technological_shutdowns} т.о.`;
    }
    if (this.operation_for_execution) {
      return `-${this.operation_for_execution}`;
    }
    return null;
  }

  get MoreDataLongString() {
    if (this.countMoreDataFields() <= 1) {
      return "";
    }
    const moreData = [];

    if (this.operation_as_needed) {
      moreData.push(`М/Н`);
    }
    if (this.operations_for_samples) {
      moreData.push(`обр`);
    }
    if (this.number_of_worker) {
      moreData.push(`на ${this.number_of_worker}x`);
    }
    if (this.operation_with_technological_shutdowns) {
      moreData.push(`${this.operation_with_technological_shutdowns} т.о`);
    }
    if (this.operation_for_execution) {
      moreData.push(`-0 ${this.operation_for_execution}`);
    }
    return moreData.join(", ");
  }
}
