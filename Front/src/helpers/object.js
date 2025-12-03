export function getObjectPropertyRecursive(object, property) {
  if (!object) return undefined;
  if (property.includes(".")) {
    const [field, newProperty] = property.split(".", 2);
    return getObjectPropertyRecursive(object[field], newProperty);
  }
  return object[property];
}
