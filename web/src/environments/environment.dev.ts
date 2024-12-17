import { EnvironmentsTypes } from './environments.types';

export const environment: EnvironmentsTypes = EnvironmentsTypes.DEVELOPMENT;

export const environmentSettings = {
  endpoint: {
    identityKey: '',
    url: 'https://escolar.atomo.ao/api_/api',
    urlImage: 'https://escolar.atomo.ao/api_',
  },
};


export const environmentSettingsUrl = {
  endpoint: {
    identityKey: '',
    url: 'http://127.0.0.1:8000/api',
    urlImage: 'http://127.0.0.1:8000',
  },
};
