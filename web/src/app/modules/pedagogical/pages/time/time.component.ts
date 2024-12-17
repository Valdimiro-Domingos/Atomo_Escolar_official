import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

@Component({
  selector: 'app-time',
  templateUrl: './time.component.html',
  styleUrls: ['./time.component.css']
})
export class TimeComponent implements OnInit {


  formulario!: FormGroup

  constructor(private build : FormBuilder) { 
    this.formulario = this.build.group({
      _ : ['']
    })
  }
   base = [{ name: 'Area Pedagógica',  url: '/dash/pedagogical-area' }, { name: 'Extra',  url: '/dash/pedagogical-area/' },{ name: 'Horário',  url: '/dash/pedagogical-area/time' }]


  ngOnInit(): void {
  }


  ngSubmit(){

  }
}
