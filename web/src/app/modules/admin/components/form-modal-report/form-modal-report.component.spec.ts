import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormModalReportComponent } from './form-modal-report.component';

describe('FormModalReportComponent', () => {
  let component: FormModalReportComponent;
  let fixture: ComponentFixture<FormModalReportComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormModalReportComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FormModalReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
