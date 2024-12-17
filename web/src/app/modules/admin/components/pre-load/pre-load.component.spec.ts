import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PreLoadComponent } from './pre-load.component';

describe('PreLoadComponent', () => {
  let component: PreLoadComponent;
  let fixture: ComponentFixture<PreLoadComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PreLoadComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PreLoadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
