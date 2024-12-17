import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';
import * as feather from 'feather-icons';

@Component({
  selector: 'form-full',
  templateUrl: 'form-full.component.html',
  styleUrls: ['./form-full.component.css'],
})
export class FormFullComponent {
  @Input() lockClose = false;
  @Input() title: string = '';
  @Input() name: string = ""
  @Input() full : boolean = false;
  @Input() isEdit: boolean = false;
  @Input() custom : boolean = false;
  @Input() route: string | null = null;
  @Input() width : number = 60;
  @Output() onClick = new EventEmitter<any>();

  @Input() padrao : boolean = true;
 @Input() color : boolean = true;


  showModal: boolean = false;

  ngOnInit() {
    feather.replace()
  }

  click(){
    this.onClick.emit()
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
