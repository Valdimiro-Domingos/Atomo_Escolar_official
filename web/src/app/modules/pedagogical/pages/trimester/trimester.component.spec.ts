import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TrimesterComponent } from './trimester.component';

describe('TrimesterComponent', () => {
  let component: TrimesterComponent;
  let fixture: ComponentFixture<TrimesterComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TrimesterComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TrimesterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
