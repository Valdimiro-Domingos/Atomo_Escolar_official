import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'filter'
})
export class FilterPipe implements PipeTransform {
  transform(items: any[], searchTerm: string): any[] {
    if (!items || !searchTerm) {
      return items;
    }
    searchTerm = searchTerm.toLowerCase() || searchTerm.toUpperCase();

    return items.filter(item => {
      return Object.values(item).some(value => {
        if (typeof value === 'string' || value instanceof String) {
          return value?.toLowerCase().includes(searchTerm) || value?.toUpperCase().includes(searchTerm);
        }
        return false;
      });
    });
  }
}
