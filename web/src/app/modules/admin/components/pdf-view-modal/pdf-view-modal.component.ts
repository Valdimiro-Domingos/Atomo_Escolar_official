import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import * as feather from 'feather-icons';

@Component({
  selector: 'app-pdf-view-modal',
  templateUrl: './pdf-view-modal.component.html',
  styleUrls: ['./pdf-view-modal.component.css']
})
export class PdfViewModalComponent implements OnInit {
  @Input() title: string = '';
  @Input() name: string = ""
  @Input() isEdit: boolean = false;
  @Output() onClick = new EventEmitter<any>();

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
