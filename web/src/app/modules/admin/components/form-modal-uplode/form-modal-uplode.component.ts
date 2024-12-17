import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Router } from '@angular/router';

@Component({
    selector: 'app-form-modal-uplode',
    templateUrl: 'form-modal-uplode.component.html',
    styleUrls: ['./form-modal-uplode.component.css']
})
export class FormModalUplodeComponent {
    @Input() lockClose = false;
    @Input() title: string = '';
    @Input() full: boolean = false;
    @Input() isEdit: boolean = false;
    @Input() custom: boolean = false;
    @Input() route: string | null = null;
    @Input() width: number = 60;
    @Output() onClick = new EventEmitter<any>();


    constructor(private router: Router) { }


    showModal: boolean = false
    ngOnInit() {

        if (this.full) {
            this.showModal = true
        }
    }

    click() {
        if (!this.lockClose) {
            this.onClick?.emit()
        }
    }

    handleShow(): void {
        this.showModal = true;
    }

    handleClose(): void {
        this.showModal = false;

        if (!this.lockClose) {
            this.click();
        }
        this.onClick.emit()
    }

    handleBackdropClick(event: MouseEvent): void {
        if (event.target === event.currentTarget) {
            if (!this.lockClose) {
                this.handleClose();
            }
        }
    }

}


