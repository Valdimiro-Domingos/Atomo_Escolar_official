import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';

@Component({
  selector: 'app-file',
  templateUrl: './file.component.html',
  styleUrls: ['./file.component.less']
})
export class FileComponent implements OnInit {
  loading : boolean = false;

  @Input() label : string = 'Uploading...';
  @Output() fileSelected: EventEmitter<File> = new EventEmitter<File>();

  constructor() { }

  ngOnInit() {}



  onFileSelected(event: any) {
    const file: File = event.target.files[0];
    this.fileSelected.emit(file);

    this.loading = true
  }

  uploadFile() {
    // Implement your file upload logic here
  }
}
