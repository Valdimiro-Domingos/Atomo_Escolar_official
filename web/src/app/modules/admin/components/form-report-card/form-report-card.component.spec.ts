import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormReportCardComponent } from './form-report-card.component';

describe('FormReportCardComponent', () => {
  let component: FormReportCardComponent;
  let fixture: ComponentFixture<FormReportCardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormReportCardComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FormReportCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
