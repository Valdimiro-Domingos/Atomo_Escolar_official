import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FormModalRemoveComponent } from './form-modal-remove.component';

describe('FormModalRemoveComponent', () => {
  let component: FormModalRemoveComponent;
  let fixture: ComponentFixture<FormModalRemoveComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FormModalRemoveComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FormModalRemoveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
