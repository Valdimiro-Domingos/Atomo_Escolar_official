import { Component, OnInit } from '@angular/core';
import * as feather from 'feather-icons'

@Component({
  selector: 'app-confirmation',
  templateUrl: './confirmation.component.html',
  styleUrls: ['./confirmation.component.css']
})
export class ConfirmationComponent implements OnInit {
  isLoading: boolean = false
  constructor() { }

    base = [{ name: 'Secretaria',  url: '/dash/secretary' }, { name: 'Confirmação',  url: '/dash/secretary/confirmation' }]
  ngOnInit(): void {
    feather.replace()
    this.isLoading = true
    setTimeout(() => {
      this.isLoading = false
    }, 500)
  }

}
