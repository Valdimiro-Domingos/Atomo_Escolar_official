import { Component, Input, OnInit } from '@angular/core';
import * as feather from 'feather-icons';

@Component({
  selector: 'app-side-bar-group',
  templateUrl: './side-bar-group.component.html',
  styleUrls: ['./side-bar-group.component.css']
})
export class SideBarGroupComponent implements OnInit {
  @Input() title: string = '';
  @Input() isIcon: boolean = false;
  @Input() user = {
      id: 0,
      name: '',
      email: '',
      departament_id: 0,
      role_id: 0
  }

  showModal: boolean = false;

  ngOnInit() {
    feather.replace()
  }

  handleShow(): void {
    this.showModal = true;
  }

  handleClose(): void {
    this.showModal = false;
  }

  handleBackdropClick(event: MouseEvent): void {
    if (event.target === event.currentTarget) {
      this.handleClose();
    }
  }

}
