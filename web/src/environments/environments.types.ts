export enum EnvironmentsTypes {
  LOCAL,
  DEVELOPMENT,
  TESTING,
  STAGING,
  PRODUCTION
}

export enum EnvironmentsTypesRemote {
  LOCAL = EnvironmentsTypes.LOCAL,
  DEV = EnvironmentsTypes.DEVELOPMENT,
  TEST = EnvironmentsTypes.TESTING,
  HML = EnvironmentsTypes.STAGING,
  PROD = EnvironmentsTypes.PRODUCTION
}
