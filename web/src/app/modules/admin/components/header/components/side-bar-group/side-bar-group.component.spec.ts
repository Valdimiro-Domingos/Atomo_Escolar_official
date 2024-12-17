import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SideBarGroupComponent } from './side-bar-group.component';

describe('SideBarGroupComponent', () => {
  let component: SideBarGroupComponent;
  let fixture: ComponentFixture<SideBarGroupComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SideBarGroupComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(SideBarGroupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
