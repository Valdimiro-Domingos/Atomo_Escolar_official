import { TestBed } from '@angular/core/testing';

import { PedagogicalService } from './pedagogical.service';

describe('PedagogicalService', () => {
  let service: PedagogicalService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PedagogicalService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
