import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NavBarHamburguerComponent } from './nav-bar-hamburguer.component';

describe('NavBarHamburguerComponent', () => {
  let component: NavBarHamburguerComponent;
  let fixture: ComponentFixture<NavBarHamburguerComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ NavBarHamburguerComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NavBarHamburguerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
