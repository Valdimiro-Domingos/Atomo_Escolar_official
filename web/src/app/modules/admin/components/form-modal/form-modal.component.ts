import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import * as feather from 'feather-icons'
@Component({
  selector: 'app-form-modal',
  templateUrl: './form-modal.component.html',
  styleUrls: ['./form-modal.component.css']
})
export class FormModalComponent implements OnInit {
  @Input() lockClose = false;
  @Input() title: string = '';
  @Input() name: string = ""
  @Input() autoHeight : boolean = true;
  @Input() full : boolean = false;
  @Input() isEdit: boolean = false;
  @Input() uplode: boolean = false;
  @Input() custom : boolean = false;
  @Input() route: string | null = null;
  @Input() width : number = 60;
  @Output() onClick = new EventEmitter<any>();




  showModal: boolean = false;

  ngOnInit() {
    feather.replace();    
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
