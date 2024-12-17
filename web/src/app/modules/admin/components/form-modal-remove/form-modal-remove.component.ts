import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import * as feather from 'feather-icons';

@Component({
  selector: 'app-form-modal-remove',
  templateUrl: './form-modal-remove.component.html',
  styleUrls: ['./form-modal-remove.component.css']
})
export class FormModalRemoveComponent implements OnInit {
  @Input() title: string = '';
  @Input() isEdit: boolean = false;
  @Input() item: any = {}
  @Input() name: string = ''
  @Input() state: string = ''
  @Input() icone: string = ''
  @Output() handleRemove = new EventEmitter<any>();
  @Output() observation = new EventEmitter<any>();
  showModal: boolean = false;
  
  observationForm! : FormGroup
  constructor(private  formBuilder: FormBuilder) { 
    this.observationForm = formBuilder.group({
      observation : ['']
    })
  }

  
  ngOnInit() {
    feather.replace()
  }

  onRemove() {
    this.handleRemove.emit();
    this.observation.emit(this.observationForm.value);
    this.showModal = false;
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
